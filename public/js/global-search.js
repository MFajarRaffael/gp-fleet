document.addEventListener("DOMContentLoaded", () => {

    const searchForm = document.querySelector(
        'form.form-inline[action="#"]'
    );

    if (!searchForm) return;

    const input = searchForm.querySelector(
        'input[name="adminlteSearch"]'
    );

    if (!input) return;

    const wrapper = searchForm.querySelector(".input-group");

    const dropdown = document.createElement("div");
    dropdown.className = "gp-search-dropdown";
    wrapper.appendChild(dropdown);

    let debounceTimer = null;

    input.addEventListener("input", () => {

        clearTimeout(debounceTimer);

        const keyword = input.value.trim();

        if (keyword.length < 2) {
            dropdown.classList.remove("show");
            dropdown.innerHTML = "";
            return;
        }

        debounceTimer = setTimeout(() => {

            fetch(`/search/global?q=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => renderDropdown(data))
                .catch(() => {
                    dropdown.classList.remove("show");
                });

        }, 300);

    });

    document.addEventListener("click", (e) => {
        if (!searchForm.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });

    function renderDropdown(data) {

        dropdown.innerHTML = "";

        const hasData =
            data.kendaraan.length ||
            data.pic.length ||
            data.dokumen.length;

        if (!hasData) {

            dropdown.innerHTML = `
                <div class="gp-search-empty">
                    Tidak ada hasil ditemukan.
                </div>
            `;

            dropdown.classList.add("show");
            return;
        }

        createSection("Kendaraan", "car", data.kendaraan);
        createSection("PIC", "user", data.pic);
        createSection("Dokumen", "file-alt", data.dokumen);

        dropdown.classList.add("show");
    }

    function createSection(title, icon, items) {

        if (!items.length) return;

        const section = document.createElement("div");
        section.className = "gp-search-section";

        section.innerHTML = `
            <div class="gp-search-title">
                <i class="fas fa-${icon}"></i> ${title}
            </div>
        `;

        items.forEach(item => {

            const link = document.createElement("a");

            link.href = item.url;
            link.className = "gp-search-item";

            link.innerHTML = `
                <div class="gp-search-icon">
                    <i class="fas fa-${icon}"></i>
                </div>

                <div class="gp-search-content">
                    <div class="gp-search-name">${item.title}</div>
                    <div class="gp-search-sub">${item.subtitle}</div>
                </div>
            `;

            section.appendChild(link);

        });

        dropdown.appendChild(section);
    }

    searchForm.addEventListener("submit", e => e.preventDefault());

});