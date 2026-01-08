document.addEventListener("DOMContentLoaded", function () {

    const steps = document.querySelectorAll(".form-step");
    const progress = document.querySelectorAll(".progress-bar li");
    const nextBtns = document.querySelectorAll(".btn.next");
    const prevBtns = document.querySelectorAll(".btn.prev");

    let currentStep = 0;

    function updateSteps() {
        steps.forEach(step => step.classList.remove("active"));
        progress.forEach(p => p.classList.remove("active"));

        steps[currentStep].classList.add("active");
        progress[currentStep].classList.add("active");

        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    nextBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateSteps();
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                updateSteps();
            }
        });
    });

    updateSteps();
});
