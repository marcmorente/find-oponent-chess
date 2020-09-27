document.addEventListener("DOMContentLoaded", () => {
    const uploadButton = document.querySelector("#upload");
    uploadButton.addEventListener("click", (e) => {
        e.preventDefault();
        upload();
    });
});

function upload() {
    const url = "/src/ajaxrequest/upload_pgn.php";
    const validPGN = [
        "application/x-chess-pgn",
        "application/da-chess-pgn",
        "application/vnd.chess-pgn",
        "text/plain",
    ];

    Swal.fire({
        title: "Selecciona un arxiu",
        showCancelButton: true,
        confirmButtonText: "Importar!",
        showLoaderOnConfirm: true,
        input: "file",
        willOpen: () => {
            const swalFile = document.querySelector(".swal2-file");
            swalFile.addEventListener("change", function () {
                const reader = new FileReader();
                reader.readAsDataURL(this.files[0]);
            });
        },
        preConfirm: (fileToUpload) => {
            const file = fileToUpload;
            if (file === null || !checkFile(validPGN, file)) return;

            const form = new FormData();
            form.append("fileToUpload", file);
            const request = new Request(url, {
                method: "POST",
                body: form,
            });

            return fetch(request)
                .then((response) => response.json())
                .catch((error) => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.value === null) {
            Swal.fire(
                "Atenció!",
                "Has de seleccionar un arxiu.",
                "warning"
            );
            return;
        }

        if (result.isConfirmed) {
            Swal.fire(
                !result.value.error ? "Importatació correcta!" : "Error",
                !result.value.error ? "Ja pots buscar les partides al sistema." : result.value.message,
                !result.value.error ? "success" : "error"
            );
        }
    });
}

function checkFile(validPGN, fileToUpload) {
    const sizeInMB = bytesToSize(fileToUpload.size);
    if (!validPGN.includes(fileToUpload.type)) {
        Swal.fire(
            "Error",
            `${fileToUpload.name} no és un arxiu vàlid.`,
            "error"
        );
        return false;
    }

    if (fileToUpload.size > 50000000) {
        Swal.fire(
            "Atenció!",
            `L'arxiu és massa gran(${sizeInMB}), màxim 50MB.`,
            "warning"
        );
        return false;
    }

    return true;
}

function bytesToSize(bytes) {
    var sizes = ["Bytes", "KB", "MB", "GB", "TB"];
    if (bytes == 0) return "0 Byte";
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
}
