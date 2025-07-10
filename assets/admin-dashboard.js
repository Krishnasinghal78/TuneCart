document.addEventListener("DOMContentLoaded", () => {
    const addSongBtn = document.getElementById("btn-add-song");
    const modal = document.getElementById("add-song-modal");
    const closeModal = document.querySelector(".close-modal");

    // Open the modal
    addSongBtn.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Close the modal
    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside of content
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
