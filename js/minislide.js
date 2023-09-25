const slides = document.querySelectorAll(".slide");
const prevButton = document.querySelector(".prev-slide");
const nextButton = document.querySelector(".next-slide");
let currentSlide = 0;

// Define a primeira slide como ativa
slides[currentSlide].classList.add("active-slide");

// Adiciona o evento de clique para o botão "Anterior"
prevButton.addEventListener("click", () => {
  goToSlide(currentSlide - 1);
});

// Adiciona o evento de clique para o botão "Próximo"
nextButton.addEventListener("click", () => {
  goToSlide(currentSlide + 1);
});

// Função para trocar de slide
function goToSlide(n) {
    slides[currentSlide].classList.remove("active-slide");
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add("active-slide");
  }
  
  // Define a função para mudar de slide automaticamente
  function autoSlide() {
    goToSlide(currentSlide + 1);
  }
  
  // Define o intervalo de tempo em milissegundos
  const intervalTime = 5000;
  
  // Define o intervalo de tempo para mudar de slide automaticamente
  let slideInterval = setInterval(autoSlide, intervalTime);
  
  // Pausa a mudança automática de slide quando o mouse está sobre o slide
slides.forEach((slide) => {
slide.addEventListener("mouseenter", () => {
      clearInterval(slideInterval);
    });
    
    slide.addEventListener("mouseleave", () => {
      slideInterval = setInterval(autoSlide, intervalTime);
    });
});
