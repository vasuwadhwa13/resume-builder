console.log("SPELL JS LOADED");

let mistakes = [];
let currentIndex = 0;
let fullText = "";
let selectedSuggestion = "";

// 🔥 collect text (ONLY SUMMARY - safe)
function getText() {
    return document.getElementById("summary")?.value || "";
}

// 🔥 open spell check
function openSpellCheck() {

    console.log("SPELL BUTTON CLICKED");

    let text = getText().trim();

    if (text === "") {
        alert("Please enter content in Summary");
        return;
    }

    fullText = text;
    currentIndex = 0;

    fetch("spell-api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "text=" + encodeURIComponent(fullText)
    })
        .then(res => res.json())
        .then(data => {
            console.log("SPELL API:", data); // 👈 add this

            if (!data.matches || data.matches.length === 0) {
                alert("No spelling errors ✅");
                return;
            }

            mistakes = data.matches;
            showError();
            showModal();
        })
        .catch(() => {
            alert("Spell check failed");
        });
}

// 🔥 show error
function showError() {

    let error = mistakes[currentIndex];
    let word = fullText.substring(error.offset, error.offset + error.length);

    // wrong word show
    document.getElementById("wrongWord").innerText = word;

    let suggestionsDiv = document.getElementById("suggestions");
    suggestionsDiv.innerHTML = "";

    // 🔥 only top 5 suggestions
    error.replacements.slice(0, 5).forEach(rep => {

        let div = document.createElement("div");
        div.innerText = rep.value;

        // 🎨 styling
        div.style.cursor = "pointer";
        div.style.padding = "8px";
        div.style.border = "1px solid #ddd";
        div.style.borderRadius = "6px";
        div.style.marginBottom = "6px";

        // ✅ selection logic
        div.onclick = () => {
            selectedSuggestion = rep.value;

            // remove highlight from all
            document.querySelectorAll("#suggestions div").forEach(el => {
                el.style.background = "";
                el.style.color = "";
            });

            // highlight selected
            div.style.background = "#2563eb";
            div.style.color = "#fff";
        };

        // 👇 optional hover effect
        div.onmouseover = () => {
            if (selectedSuggestion !== rep.value) {
                div.style.background = "#e5e7eb";
            }
        };

        div.onmouseout = () => {
            if (selectedSuggestion !== rep.value) {
                div.style.background = "";
            }
        };

        suggestionsDiv.appendChild(div);
    });
}

// 🔥 change
function changeWord() {

    if (!selectedSuggestion) {
        alert("Select suggestion first");
        return;
    }

    let error = mistakes[currentIndex];

    let diff = selectedSuggestion.length - error.length;

    fullText =
        fullText.substring(0, error.offset) +
        selectedSuggestion +
        fullText.substring(error.offset + error.length);

    // 🔥 fix offsets for next words
    for (let i = currentIndex + 1; i < mistakes.length; i++) {
        mistakes[i].offset += diff;
    }
    // 🔥 important fix
    applyChanges();

    currentIndex++;

    if (currentIndex >= mistakes.length) {
        closeSpellCheck();
        return;
    }

    showError();
}

// 🔥 apply
function applyChanges() {
    document.getElementById("summary").value = fullText;
}

// 🔥 modal
function showModal() {
    document.getElementById("spellModal").style.display = "flex";
}

function closeSpellCheck() {
    document.getElementById("spellModal").style.display = "none";
}
function changeAll() {
    if (!selectedSuggestion) {
        alert("Select suggestion first");
        return;
    }

    let wrong = mistakes[currentIndex].context.text;

    let regex = new RegExp(wrong, "gi");
    fullText = fullText.replace(regex, selectedSuggestion);

    applyChanges();
    closeSpellCheck();
}

function ignoreWord() {
    currentIndex++;

    if (currentIndex >= mistakes.length) {
        closeSpellCheck();
        return;
    }

    showError();
}