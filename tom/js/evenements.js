const recherche = document.getElementById("barre-de-recherche");
const liste = document.getElementById("liste-evenements");

const evenements = liste.querySelectorAll("a");

recherche.addEventListener("input", function () {
    const texte = recherche.value
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();

    let resultatTrouve = false;

    evenements.forEach(function (evenement) {
        const nom = evenement.textContent
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        const motsCles = evenement.dataset.motsCles
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        if (
            texte !== "" &&
            (nom.includes(texte) || motsCles.includes(texte))
        ) {
            evenement.parentElement.style.display = "block";
            resultatTrouve = true;
        } else {
            evenement.parentElement.style.display = "none";
        }
    });

    liste.style.display = resultatTrouve ? "block" : "none";
});