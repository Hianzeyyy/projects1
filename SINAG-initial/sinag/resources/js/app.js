import './bootstrap';

// SINAG Portal Custom JS

document.addEventListener('DOMContentLoaded', function() {
    // Example: Safe-Walk Timer (placeholder logic)
    const timerDisplay = document.querySelector('.sinag-timer');
    const startBtn = document.querySelector('.sinag-timer-start');
    const panicBtn = document.querySelector('.sinag-timer-panic');
    let timerInterval;
    let timeLeft = 600; // 10 minutes in seconds

    if (startBtn && timerDisplay) {
        startBtn.addEventListener('click', function() {
            clearInterval(timerInterval);
            timeLeft = 600;
            timerDisplay.textContent = formatTime(timeLeft);
            timerInterval = setInterval(() => {
                timeLeft--;
                timerDisplay.textContent = formatTime(timeLeft);
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = '00:00:00';
                }
            }, 1000);
        });
    }
    if (panicBtn) {
        panicBtn.addEventListener('click', function() {
            alert('Panic alert sent to admin!');
        });
    }

    function formatTime(seconds) {
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        return `00:${m}:${s}`;
    }
});