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

const closeFilteringFormBtn = document.querySelector(".filtering-card__close-filter-form");
const openFilteringFormBtn = document.querySelector(".filtering-card__open-filter-btn");
const filteringForm = document.querySelector(".filtering-card__form");

openFilteringFormBtn.addEventListener("click", () => {
  filteringForm.classList.add("active");
});

closeFilteringFormBtn.addEventListener("click", () => {
  filteringForm.classList.remove("active");
});