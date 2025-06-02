function showDetails(title, description) {
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalDesc').textContent = description;
  document.getElementById('productModal').style.display = 'block';
}

function openEnquiry(productName) {
  document.getElementById("enquiryProductHeading").innerText = productName;
  document.getElementById("enquiryProduct").value = productName;
  document.getElementById("enquiryName").value = "";
  document.getElementById("enquiryEmail").value = "";
  document.getElementById("enquiryPhone").value = "";
  document.getElementById("enquiryMessage").value = "";
  document.getElementById("enquiryModal").style.display = "block";
}


function openQuote() {
  document.getElementById('quoteModal').style.display = 'block';
  document.getElementById('quoteResult').textContent = '';
}

function openPaperWeightModal() {
  document.getElementById("paperWeightModal").style.display = "block";
  document.getElementById('paperWeightResult').textContent = '';
}

function closeModal() {
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modal => modal.style.display = "none");
}

function calculateQuote(event) {
  event.preventDefault();
  const length = parseFloat(document.getElementById("length").value);
  const breadth = parseFloat(document.getElementById("breadth").value);
  const height = parseFloat(document.getElementById("height").value);
  const thickness = parseFloat(document.getElementById("thickness").value);

  const thicknessRates = {
    1: 60, 2: 85, 3: 105, 4: 140, 5: 160,
    6: 180, 8: 200, 10: 320, 12: 380, 18: 650
  };

  if (!thicknessRates.hasOwnProperty(thickness)) {
    document.getElementById("quoteResult").textContent =
      `Unsupported thickness value. Use: ${Object.keys(thicknessRates).join(", ")} mm`;
    return;
  }

  const x = length * breadth * 2;
  const y = breadth * height * 2;
  const z = height * length * 2;
  const area = (x + y + z) / 144;
  const basePrice = area * thicknessRates[thickness];
  const finalPrice = basePrice * 1.45;

  document.getElementById("quoteResult").textContent =
    `Estimated Price (including labor): ₹${finalPrice.toFixed(2)}`;
}

function calculatePaperWeight(event) {
  event.preventDefault();
  const length = parseFloat(document.getElementById("pwLength").value);
  const breadth = parseFloat(document.getElementById("pwBreadth").value);
  const thickness = parseInt(document.getElementById("pwThickness").value);

  const thicknessRates = {
    1: 60, 2: 85, 3: 105, 4: 140, 5: 160,
    6: 180, 8: 200, 10: 320, 12: 380, 18: 650
  };

  if (!thicknessRates.hasOwnProperty(thickness)) {
    document.getElementById("paperWeightResult").textContent =
      "Invalid thickness. Use 1,2,3,4,5,6,8,10,12,18 mm.";
    return;
  }

  const areaSqInch = length * breadth * 2;
  const areaSqFt = areaSqInch / 144;
  const base = areaSqFt * thicknessRates[thickness] ;
  const finalPrice = base * 1.45;

  document.getElementById("paperWeightResult").textContent =
    `Estimated Paper Weight Cost: ₹${finalPrice.toFixed(2)}`;
}

function filterProducts() {
  const input = document.getElementById('productSearch').value.toLowerCase();
  const products = document.getElementsByClassName('product-card');
  let visibleCount = 0;

  for (let product of products) {
    const title = product.getElementsByTagName('h4')[0].textContent.toLowerCase();
    product.style.display = title.includes(input) ? "" : "none";
    if (title.includes(input)) visibleCount++;
  }

  document.getElementById('noResultsMessage').style.display =
    visibleCount === 0 ? "block" : "none";
}

// 🔄 Image carousel logic
function changeImage(button, direction) {
  const carousel = button.closest('.image-carousel');
  const images = carousel.querySelectorAll('.carousel-image');
  let currentIndex = Array.from(images).findIndex(img => img.style.display !== 'none');

  images[currentIndex].style.display = 'none';
  const nextIndex = (currentIndex + direction + images.length) % images.length;
  images[nextIndex].style.display = 'block';
}

setInterval(() => {
  document.querySelectorAll('.image-carousel').forEach(carousel => {
    const rightArrow = carousel.querySelector('.right-arrow');
    if (rightArrow) changeImage(rightArrow, 1);
  });
}, 5000);

// 🔍 Escape & outside modal click
window.onclick = function(event) {
  if (event.target.classList.contains('modal')) closeModal();
};

window.addEventListener('keydown', (e) => {
  if (e.key === "Escape") closeModal();
});

// 🔍 Fullscreen viewer
let viewerImages = [];
let viewerIndex = 0;

function openImageViewer(images, index) {
  viewerImages = images;
  viewerIndex = index;
  document.getElementById("viewerImage").src = viewerImages[viewerIndex];
  document.getElementById("imageViewer").style.display = "block";
}

function changeViewerImage(direction) {
  if (!viewerImages.length) return;
  viewerIndex = (viewerIndex + direction + viewerImages.length) % viewerImages.length;
  document.getElementById("viewerImage").src = viewerImages[viewerIndex];
}
function preloadImages(imgArray) {
  imgArray.forEach(src => {
    const img = new Image();
    img.src = src;
  });
}
let currentOrderProduct = "";


function submitEnquiry(event) {
  event.preventDefault();

  const name = document.getElementById("enquiryName").value;
  const email = document.getElementById("enquiryEmail").value;
  const phone = document.getElementById("enquiryPhone").value;
  const product = document.getElementById("enquiryProduct").value;
  const message = document.getElementById("enquiryMessage").value;

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("phone", phone);
  formData.append("product", product);
  formData.append("message", message);

  // Show loading message
  const loader = document.getElementById("enquiryLoader");
  loader.style.display = "block";

  fetch("send.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      alert("Enquiry sent successfully!");
      closeModal();
    })
    .catch(err => {
      alert("Something went wrong. Please try again.");
      console.error(err);
    })
    .finally(() => {
      // ✅ Always hide the loader, even if there's an error
      loader.style.display = "none";
    });
}

const bgImages = [
  'images/background/image1.jpg',
  'images/background/image2.jpg',
  'images/background/image3.jpg',
  'images/background/image5.jpg',
  'images/background/image6.jpg',
  'images/background/image7.jpg'
];

let bgIndex = 0;

function cycleBackground() {
  const bgContainer = document.getElementById('dynamicBg');
  bgContainer.style.backgroundImage = `url(${bgImages[bgIndex]})`;
  bgIndex = (bgIndex + 1) % bgImages.length;
}

setInterval(cycleBackground, 5000); // Change every 5 seconds
window.onload = cycleBackground; // Set initial image


//need help function 
function openGeneralEnquiry() {
  document.getElementById('enquiryProduct').value = "";
  document.getElementById('enquiryModal').style.display = 'block';
  document.getElementById('enquiryProductHeading').innerHTML = "<span style='color:#00ffe1;'>General Enquiry</span>";

  // Optional toast message
  const toast = document.createElement('div');
  toast.textContent = "Opening general enquiry...";
  toast.style.position = "fixed";
  toast.style.bottom = "80px";
  toast.style.right = "20px";
  toast.style.background = "#00ffe1";
  toast.style.color = "#000";
  toast.style.padding = "10px 20px";
  toast.style.borderRadius = "20px";
  toast.style.boxShadow = "0 0 10px rgba(0,255,225,0.5)";
  toast.style.zIndex = "9999";
  toast.style.fontWeight = "bold";
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 2000);
}

