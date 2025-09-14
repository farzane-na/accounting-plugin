document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".filtering-card__video-cover").forEach(function(cover) {
        cover.addEventListener("click", function() {
            let wrapper = cover.closest(".filtering-card__video-wrapper");
            let video = wrapper.querySelector(".filtering-card__video");

            cover.style.display = "none";
            video.style.display = "block";
            video.play();
        });
    });
});
