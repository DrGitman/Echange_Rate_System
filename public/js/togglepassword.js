/**
 * Password visibility toggle handler.
 * Works with any button that has:
 *   class="toggle-password"
 *   data-target="#inputId"
 */
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".toggle-password").forEach(function (button) {

        button.addEventListener("click", function () {
            const targetSelector = this.dataset.target;
            const input = document.querySelector(targetSelector);
            if (!input) return;

            const isHidden = input.type === "password";
            input.type = isHidden ? "text" : "password";

            // Swap the icon
            const icon = this.querySelector(".material-symbols-outlined");
            if (icon) {
                icon.textContent = isHidden ? "visibility_off" : "visibility";
            }
        });

    });

});