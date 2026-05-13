(function () {
    const widget = document.querySelector("[data-theme-cat-widget]");

    if (!widget) {
        return;
    }

    const cat = widget.querySelector("[data-theme-cat]");
    const toggle = widget.querySelector("[data-theme-cat-toggle]");
    const frameBase = "/portfolio/components/theme-cat-toggle/frames/";
    const frameDelay = 220;

    const catFrames = [
        "sleep-1.png",
        "sleep-2.png",
        "sleep-3.png",
        "sleep-4.png",
        "sleep-5.png"
    ];

    const wakeFrames = catFrames.slice().reverse();
    const sleepFrames = catFrames;

    let isLight = localStorage.getItem("portfolio-theme") === "light";
    let isAnimating = false;

    catFrames.forEach(function (frame) {
        const image = new Image();
        image.src = frameBase + frame;
    });

    function setFrame(frame) {
        cat.classList.add("is-switching-frame");

        window.setTimeout(function () {
            cat.src = frameBase + frame;
            cat.classList.remove("is-switching-frame");
            cat.classList.remove("is-animating");
            void cat.offsetWidth;
            cat.classList.add("is-animating");
        }, 70);
    }

    function applyTheme(light) {
        document.body.classList.toggle("light-mode", light);
        localStorage.setItem("portfolio-theme", light ? "light" : "dark");
        toggle.setAttribute("aria-pressed", light ? "true" : "false");
    }

    function playFrames(frames, options) {
        isAnimating = true;
        toggle.disabled = true;

        let index = 0;
        const midpoint = options && typeof options.midpoint === "number" ? options.midpoint : -1;

        const timer = window.setInterval(function () {
            setFrame(frames[index]);

            if (index === midpoint && options && typeof options.onMidpoint === "function") {
                options.onMidpoint();
            }

            index++;

            if (index >= frames.length) {
                window.clearInterval(timer);
                isAnimating = false;
                toggle.disabled = false;

                if (options && typeof options.onDone === "function") {
                    options.onDone();
                }
            }
        }, frameDelay);
    }

    applyTheme(isLight);
    setFrame(isLight ? catFrames[0] : catFrames[catFrames.length - 1]);

    toggle.addEventListener("click", function () {
        if (isAnimating) {
            return;
        }

        if (!isLight) {
            playFrames(wakeFrames, {
                midpoint: 2,
                onMidpoint: function () {
                    applyTheme(true);
                },
                onDone: function () {
                    isLight = true;
                }
            });
        } else {
            playFrames(sleepFrames, {
                midpoint: 2,
                onMidpoint: function () {
                    applyTheme(false);
                },
                onDone: function () {
                    isLight = false;
                }
            });
        }
    });
})();
