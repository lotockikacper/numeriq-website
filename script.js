(() => {
    "use strict";

    const header = document.querySelector(".site-header");
    const menuButton = document.querySelector(".menu-toggle");
    const mainNav = document.querySelector(".main-nav");
    const navLinks = document.querySelectorAll(".main-nav a");
    const form = document.querySelector("#contact-form");
    const statusBox = document.querySelector("#form-status");
    const submitButton = form?.querySelector("button[type='submit']");

    const setHeaderState = () => header?.classList.toggle("scrolled", window.scrollY > 12);
    setHeaderState();
    window.addEventListener("scroll", setHeaderState, { passive: true });

    const closeMenu = () => {
        menuButton?.setAttribute("aria-expanded", "false");
        mainNav?.classList.remove("open");
        document.body.classList.remove("menu-open");
    };

    menuButton?.addEventListener("click", () => {
        const open = menuButton.getAttribute("aria-expanded") === "true";
        menuButton.setAttribute("aria-expanded", String(!open));
        mainNav?.classList.toggle("open", !open);
        document.body.classList.toggle("menu-open", !open);
    });

    navLinks.forEach((link) => link.addEventListener("click", closeMenu));

    document.addEventListener("click", (event) => {
        if (!mainNav?.classList.contains("open")) return;
        const target = event.target;
        if (
            target instanceof Node &&
            !mainNav.contains(target) &&
            !menuButton?.contains(target)
        ) {
            closeMenu();
        }
    });

    const revealElements = document.querySelectorAll(".reveal");
    if (
        "IntersectionObserver" in window &&
        !window.matchMedia("(prefers-reduced-motion: reduce)").matches
    ) {
        const observer = new IntersectionObserver(
            (entries, currentObserver) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add("visible");
                    currentObserver.unobserve(entry.target);
                });
            },
            { threshold: 0.12 }
        );

        revealElements.forEach((element) => observer.observe(element));
    } else {
        revealElements.forEach((element) => element.classList.add("visible"));
    }

    const setStatus = (message, type = "error") => {
        if (!statusBox) return;
        statusBox.textContent = message;
        statusBox.className = `form-status visible ${type}`;
        statusBox.scrollIntoView({ behavior: "smooth", block: "nearest" });
    };

    const clearStatus = () => {
        if (!statusBox) return;
        statusBox.textContent = "";
        statusBox.className = "form-status";
    };

    const setLoading = (loading) => {
        if (!submitButton) return;
        submitButton.disabled = loading;
        submitButton.classList.toggle("loading", loading);
        submitButton.setAttribute("aria-busy", String(loading));
    };

    const validateForm = () => {
        if (!form) return false;

        let valid = true;
        form.querySelectorAll("[required]").forEach((field) => {
            let fieldValid = true;

            if (field instanceof HTMLInputElement && field.type === "checkbox") {
                fieldValid = field.checked;
            } else if (
                field instanceof HTMLInputElement ||
                field instanceof HTMLTextAreaElement ||
                field instanceof HTMLSelectElement
            ) {
                fieldValid = field.value.trim() !== "" && field.checkValidity();
            }

            field.classList.toggle("field-invalid", !fieldValid);
            if (!fieldValid) valid = false;
        });

        return valid;
    };

    const getFormspreeError = (data, response) => {
        if (response.status === 429) {
            return "Wysłano zbyt wiele wiadomości w krótkim czasie. Odczekaj chwilę i spróbuj ponownie.";
        }

        if (Array.isArray(data?.errors) && data.errors.length > 0) {
            return data.errors
                .map((error) => error?.message)
                .filter(Boolean)
                .join(" ");
        }

        return "Nie udało się wysłać wiadomości. Spróbuj ponownie albo sprawdź połączenie z internetem.";
    };

    form?.querySelectorAll("input, textarea, select").forEach((field) => {
        field.addEventListener("input", () => field.classList.remove("field-invalid"));
        field.addEventListener("change", () => field.classList.remove("field-invalid"));
    });

    form?.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearStatus();

        if (!validateForm()) {
            setStatus("Uzupełnij poprawnie wszystkie wymagane pola.");
            form.querySelector(".field-invalid")?.focus();
            return;
        }

        setLoading(true);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: {
                    "Accept": "application/json"
                }
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(getFormspreeError(data, response));
            }

            form.reset();
            setStatus(
                "Wiadomość została wysłana. Dziękuję — odezwę się tak szybko, jak będzie to możliwe!",
                "success"
            );
        } catch (error) {
            console.error(error);
            setStatus(
                error instanceof Error
                    ? error.message
                    : "Wystąpił błąd podczas wysyłania. Spróbuj ponownie później."
            );
        } finally {
            setLoading(false);
        }
    });

    const year = document.querySelector("#year");
    if (year) year.textContent = String(new Date().getFullYear());
})();
