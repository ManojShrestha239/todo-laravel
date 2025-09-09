// CSRF Token setup
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (csrfToken) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;
}

// Task management functions
class TaskManager {
    constructor() {
        this.initEventListeners();
    }

    initEventListeners() {
        // Toggle task completion
        document.addEventListener("click", (e) => {
            if (e.target.closest(".task-toggle-btn")) {
                e.preventDefault();
                const button = e.target.closest(".task-toggle-btn");
                const taskId = button.dataset.taskId;
                this.toggleTask(taskId);
            }
        });

        // Delete task
        document.addEventListener("click", (e) => {
            if (e.target.closest(".task-delete-btn")) {
                e.preventDefault();
                const button = e.target.closest(".task-delete-btn");
                const taskId = button.dataset.taskId;
                if (confirm("Are you sure you want to delete this task?")) {
                    this.deleteTask(taskId);
                }
            }
        });

        // Update task form submission
        document.addEventListener("submit", (e) => {
            if (e.target.classList.contains("task-update-form")) {
                e.preventDefault();
                this.updateTask(e.target);
            }
        });

        // Create task form submission
        document.addEventListener("submit", (e) => {
            if (e.target.classList.contains("task-create-form")) {
                e.preventDefault();
                this.createTask(e.target);
            }
        });

        // Search and filter form
        document.addEventListener("submit", (e) => {
            if (e.target.classList.contains("task-filter-form")) {
                e.preventDefault();
                this.filterTasks(e.target);
            }
        });

        // Clear filters
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("clear-filters-btn")) {
                e.preventDefault();
                this.clearFilters();
            }
        });
    }

    async toggleTask(taskId) {
        try {
            this.showLoading();
            const response = await axios.patch(
                `/tasks/${taskId}/toggle`,
                {},
                {
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                }
            );

            if (response.data.success) {
                this.showMessage(response.data.message, "success");
                await this.refreshTasks();
            }
        } catch (error) {
            console.error("Error toggling task:", error);
            this.showMessage("Error updating task. Please try again.", "error");
        } finally {
            this.hideLoading();
        }
    }

    async deleteTask(taskId) {
        try {
            this.showLoading();
            const response = await axios.delete(`/tasks/${taskId}`, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (response.data.success) {
                this.showMessage(response.data.message, "success");
                await this.refreshTasks();
            }
        } catch (error) {
            console.error("Error deleting task:", error);
            this.showMessage("Error deleting task. Please try again.", "error");
        } finally {
            this.hideLoading();
        }
    }

    async updateTask(form) {
        try {
            this.showLoading();
            const formData = new FormData(form);
            const taskId = form.dataset.taskId;

            const response = await axios.post(`/tasks/${taskId}`, formData, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-HTTP-Method-Override": "PUT",
                },
            });

            if (response.data.success) {
                this.showMessage(response.data.message, "success");
                this.closeModal(`edit-modal-${taskId}`);
                await this.refreshTasks();
            }
        } catch (error) {
            console.error("Error updating task:", error);
            if (error.response?.data?.errors) {
                this.showValidationErrors(error.response.data.errors);
            } else {
                this.showMessage(
                    "Error updating task. Please try again.",
                    "error"
                );
            }
        } finally {
            this.hideLoading();
        }
    }

    async createTask(form) {
        try {
            this.showLoading();
            const formData = new FormData(form);

            const response = await axios.post("/tasks", formData, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (response.data.success) {
                this.showMessage(response.data.message, "success");
                form.reset(); // Clear the form
                // Optionally redirect to tasks page after a brief delay
                setTimeout(() => {
                    window.location.href = "/tasks";
                }, 1500);
            }
        } catch (error) {
            console.error("Error creating task:", error);
            if (error.response?.data?.errors) {
                this.showValidationErrors(error.response.data.errors);
            } else {
                this.showMessage(
                    "Error creating task. Please try again.",
                    "error"
                );
            }
        } finally {
            this.hideLoading();
        }
    }

    async filterTasks(form) {
        try {
            this.showLoading();
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            const response = await axios.get(
                `/tasks-data?${params.toString()}`,
                {
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                }
            );

            this.updateTasksDisplay(response.data);
        } catch (error) {
            console.error("Error filtering tasks:", error);
            this.showMessage(
                "Error filtering tasks. Please try again.",
                "error"
            );
        } finally {
            this.hideLoading();
        }
    }

    async clearFilters() {
        // Clear form inputs
        const form = document.querySelector(".task-filter-form");
        if (form) {
            form.reset();
        }

        // Refresh tasks without filters
        await this.refreshTasks();
    }

    async refreshTasks() {
        try {
            const response = await axios.get("/tasks-data", {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            this.updateTasksDisplay(response.data);
        } catch (error) {
            console.error("Error refreshing tasks:", error);
            this.showMessage(
                "Error refreshing tasks. Please reload the page.",
                "error"
            );
        }
    }

    updateTasksDisplay(data) {
        // Update pending tasks
        const pendingContainer = document.getElementById(
            "pending-tasks-container"
        );
        if (pendingContainer) {
            pendingContainer.innerHTML = this.renderTasks(
                data.pendingTasks,
                "pending"
            );
        }

        // Update completed tasks
        const completedContainer = document.getElementById(
            "completed-tasks-container"
        );
        if (completedContainer) {
            completedContainer.innerHTML = this.renderTasks(
                data.completedTasks,
                "completed"
            );
        }

        // Update statistics
        this.updateStatistics(data.statistics);

        // Update task counts
        this.updateTaskCounts(data.statistics);
    }

    renderTasks(tasks, type) {
        if (tasks.length === 0) {
            return this.renderEmptyState(type);
        }

        return tasks.map((task) => this.renderTaskCard(task)).join("");
    }

    renderEmptyState(type) {
        const isCompleted = type === "completed";
        const icon = isCompleted ? "🎯" : "✅";
        const title = isCompleted
            ? "No completed tasks yet"
            : "No pending tasks";
        const message = isCompleted
            ? "Complete some tasks to see them here."
            : "Great job! You've completed all your tasks.";
        const button = isCompleted
            ? ""
            : `
            <a href="/tasks/create" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200 mt-4">
                Add Your First Task
            </a>
        `;

        return `
            <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                <div class="text-gray-400 text-6xl mb-4">${icon}</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">${title}</h3>
                <p class="text-gray-500">${message}</p>
                ${button}
            </div>
        `;
    }

    renderTaskCard(task) {
        const isOverdue =
            task.due_date &&
            new Date(task.due_date) < new Date() &&
            !task.completed;
        const cardClasses = `bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow ${
            isOverdue ? "border-l-4 border-l-red-500 bg-red-50" : ""
        }`;
        const titleClasses = `text-lg font-medium text-gray-900 ${
            task.completed ? "line-through text-gray-500" : ""
        }`;
        const descriptionClasses = `text-gray-600 mt-1 ${
            task.completed ? "line-through" : ""
        }`;

        const dueDateFormatted = task.due_date
            ? new Date(task.due_date).toLocaleDateString("en-US", {
                  month: "short",
                  day: "numeric",
                  year: "numeric",
              })
            : null;

        const createdAt = new Date(task.created_at).toLocaleDateString(
            "en-US",
            {
                month: "short",
                day: "numeric",
                year: "numeric",
            }
        );

        return `
            <div class="${cardClasses}">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-3">
                            <button type="button" data-task-id="${task.id}"
                                class="task-toggle-btn flex-shrink-0 w-5 h-5 rounded border-2 transition-colors ${
                                    task.completed
                                        ? "bg-green-500 border-green-500 text-white"
                                        : "border-gray-300 hover:border-green-400"
                                }">
                                ${
                                    task.completed
                                        ? '<svg class="w-3 h-3 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>'
                                        : ""
                                }
                            </button>
                            <div class="flex-1">
                                <h3 class="${titleClasses}">${task.title}</h3>
                                ${
                                    task.description
                                        ? `<p class="${descriptionClasses}">${
                                              task.description.length > 150
                                                  ? task.description.substring(
                                                        0,
                                                        150
                                                    ) + "..."
                                                  : task.description
                                          }</p>`
                                        : ""
                                }
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                    ${
                                        dueDateFormatted
                                            ? `
                                        <span class="flex items-center space-x-1 ${
                                            isOverdue
                                                ? "text-red-600 font-medium"
                                                : ""
                                        }">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>${dueDateFormatted}${
                                                  isOverdue ? " (Overdue)" : ""
                                              }</span>
                                        </span>
                                    `
                                            : ""
                                    }
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>${createdAt}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <button type="button" onclick="openModal('edit-modal-${
                            task.id
                        }')"
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button type="button" data-task-id="${task.id}"
                            class="task-delete-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                ${this.renderEditModal(task)}
            </div>
        `;
    }

    renderEditModal(task) {
        const dueDateValue = task.due_date ? task.due_date.split("T")[0] : "";

        return `
            <div id="edit-modal-${
                task.id
            }" class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Task</h3>
                        <button type="button" onclick="closeModal('edit-modal-${
                            task.id
                        }')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form class="task-update-form" data-task-id="${task.id}">
                        <div class="space-y-4">
                            <div>
                                <label for="edit-title-${
                                    task.id
                                }" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                <input type="text" id="edit-title-${
                                    task.id
                                }" name="title" value="${task.title}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label for="edit-description-${
                                    task.id
                                }" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="edit-description-${
                                    task.id
                                }" name="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">${
                                        task.description || ""
                                    }</textarea>
                            </div>
                            <div>
                                <label for="edit-due_date-${
                                    task.id
                                }" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                <input type="date" id="edit-due_date-${
                                    task.id
                                }" name="due_date" value="${dueDateValue}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="completed" value="0">
                                <input type="checkbox" id="edit-completed-${
                                    task.id
                                }" name="completed" value="1" ${
            task.completed ? "checked" : ""
        }
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="edit-completed-${
                                    task.id
                                }" class="ml-2 text-sm text-gray-700">Mark as completed</label>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeModal('edit-modal-${
                                task.id
                            }')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
    }

    updateStatistics(stats) {
        const statisticsContainer = document.getElementById(
            "statistics-container"
        );
        if (statisticsContainer && stats.total > 0) {
            statisticsContainer.style.display = "block";

            const totalElement =
                statisticsContainer.querySelector(".total-count");
            const pendingElement =
                statisticsContainer.querySelector(".pending-count");
            const completedElement =
                statisticsContainer.querySelector(".completed-count");
            const overdueElement =
                statisticsContainer.querySelector(".overdue-count");

            if (totalElement) totalElement.textContent = stats.total;
            if (pendingElement) pendingElement.textContent = stats.pending;
            if (completedElement)
                completedElement.textContent = stats.completed;
            if (overdueElement) overdueElement.textContent = stats.overdue;
        } else if (statisticsContainer) {
            statisticsContainer.style.display = "none";
        }
    }

    updateTaskCounts(stats) {
        const pendingCount = document.getElementById("pending-count");
        const completedCount = document.getElementById("completed-count");

        if (pendingCount) pendingCount.textContent = `(${stats.pending})`;
        if (completedCount) completedCount.textContent = `(${stats.completed})`;
    }

    showLoading() {
        // You can implement a loading spinner here
        document.body.style.cursor = "wait";
    }

    hideLoading() {
        document.body.style.cursor = "default";
    }

    showMessage(message, type = "info") {
        // Create a toast notification
        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === "success"
                ? "bg-green-100 text-green-800 border border-green-200"
                : type === "error"
                ? "bg-red-100 text-red-800 border border-red-200"
                : "bg-blue-100 text-blue-800 border border-blue-200"
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    showValidationErrors(errors) {
        Object.keys(errors).forEach((field) => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add("border-red-500");

                // Remove existing error message
                const existingError =
                    input.parentNode.querySelector(".error-message");
                if (existingError) {
                    existingError.remove();
                }

                // Add new error message
                const errorDiv = document.createElement("div");
                errorDiv.className = "error-message text-red-500 text-sm mt-1";
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);

                // Remove error styling on input
                input.addEventListener(
                    "input",
                    function () {
                        this.classList.remove("border-red-500");
                        const errorMsg =
                            this.parentNode.querySelector(".error-message");
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    },
                    { once: true }
                );
            }
        });
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add("hidden");
        }
    }
}

// Modal functions (global scope for onclick handlers)
window.openModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove("hidden");
    }
};

window.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add("hidden");
    }
};

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    new TaskManager();
});
