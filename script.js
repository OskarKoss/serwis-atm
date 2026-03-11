 const icon = document.getElementById("ikona");

    icon.addEventListener("click", () => {
        if (icon.classList.contains("fa-sun")) {
            icon.classList.remove("fa-sun", "fa-solid");
            icon.classList.add("fa-moon", "fa-regular");

            document.getElementById("body").style.transition = "all 1s";
            document.getElementById('ikona').style.color = 'white';
            document.getElementById('body').style.backgroundColor = '#1E1E1E';
            document.getElementById('body').style.color = 'white';
            document.getElementById('link').style.color = 'white';
        } 
        else if (icon.classList.contains("fa-moon")) {
            icon.classList.remove("fa-moon", "fa-regular");
            icon.classList.add("fa-sun", "fa-solid");

            document.getElementById("body").style.transition = "all 1s";
            document.getElementById('ikona').style.color = 'black';
            document.getElementById('body').style.backgroundColor = 'white';
            document.getElementById('body').style.color = 'black';
            document.getElementById('link').style.color = 'black';
        }
    });

function updateForm() {
        const naprawa = document.getElementById('naprawa').checked;
        const przeglad = document.getElementById('przeglad').checked;
        const form = document.getElementById('dynamicForm');

        form.innerHTML = '';

        if (naprawa || przeglad) {
            if (naprawa) {
                form.innerHTML += `
                    <label>Czas:<br><input type="number" name="czas" placeholder="rbh" class="formula"/></label><br><br>
                    <label>Dojazd:<br><input type="number" name="dojazd" placeholder="km" class="formula"/></label><br><br>
                    <label>Części:<br><textarea name="czesci" rows="4" cols="40" placeholder="Użyte części" class="formula"></textarea></label><br><br>
                    <label>Opis/Uwagi:<br><textarea name="nota" rows="4" cols="40" placeholder="Opis lub uwagi" class="formula"></textarea></label><br><br>
                `;
            } else if (przeglad) {
                form.innerHTML += `
                    <label>Dojazd:<br><input type="number" name="dojazd" placeholder="km" class="formula"/></label><br><br>
                    <label>Opis/Uwagi:<br><textarea name="nota" rows="4" cols="40" placeholder="Opis lub uwagi" class="formula"></textarea></label><br><br>
                `;
            }
        }
    }

    function validateFileUpload() {
        const fileInput = document.getElementById("photo");
        if (!fileInput.value) {
            alert("Musisz dodać jeden załącznik (zdjęcie lub PDF)!");
            return false;
        }
        return true;
    }