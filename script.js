const productCatalog = window.productCatalog || {};

const detailPanelEl = document.getElementById('productDetailPanel');
const detailHeroImageEl = document.getElementById('detailHeroImage');
const detailTaglineEl = document.getElementById('detailTagline');
const detailTitleEl = document.getElementById('detailTitle');
const detailDescriptionEl = document.getElementById('detailDescription');
const detailSizeEl = document.getElementById('detailSize');
const detailFinishEl = document.getElementById('detailFinish');
const detailLeadEl = document.getElementById('detailLeadTime');
const detailPriceEl = document.getElementById('detailPrice');
const detailFeaturesEl = document.getElementById('detailFeatures');
const detailThumbsEl = document.getElementById('detailThumbs');
const detailPrimaryCtaEl = document.getElementById('detailPrimaryCta');
const detailWhatsappCtaEl = document.getElementById('detailWhatsappCta');
const heroCanvasEl = document.getElementById('heroCanvas');
const heroBackgroundEl = document.getElementById('dynamicBg');
const finePointerQuery = window.matchMedia ? window.matchMedia('(pointer: fine)') : null;
const motionPreferenceQuery = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;

let activeProductId = '';
let activeImageIndex = 0;
let teardownCanvasAnimation = null;
let teardownParallaxScene = null;

function openProductDetail(productId) {
  const product = productCatalog[productId];
  if (!product || !detailPanelEl) return;

  activeProductId = productId;
  activeImageIndex = 0;

  const galleryImages = Array.isArray(product.images) && product.images.length
    ? product.images
    : ['images/background/image1.webp'];
  if (!product.images || !product.images.length) {
    product.images = galleryImages;
  }

  detailTaglineEl.textContent = product.tagline;
  detailTitleEl.textContent = product.name;
  detailDescriptionEl.textContent = product.description;
  detailSizeEl.textContent = product.specs.size;
  detailFinishEl.textContent = product.specs.finish;
  detailLeadEl.textContent = product.specs.lead;
  detailPriceEl.textContent = product.price;
  detailHeroImageEl.src = galleryImages[0];

  detailFeaturesEl.innerHTML = product.features
    .map(feature => `<li><span>${feature}</span></li>`)
    .join('');

  renderDetailGallery(galleryImages);
  schedulePreload(galleryImages.slice(1));

  detailPrimaryCtaEl.onclick = () => openEnquiry(product.name);
  detailWhatsappCtaEl.onclick = () => {
    const encoded = encodeURIComponent(product.whatsapp);
    window.open(`https://wa.me/917032174014?text=${encoded}`, '_blank');
  };

  detailPanelEl.classList.add('visible');
  document.body.classList.add('detail-open');
}

function renderDetailGallery(images = []) {
  if (!detailThumbsEl) return;
  detailThumbsEl.innerHTML = '';
  images.forEach((src, index) => {
    const button = document.createElement('button');
    button.className = `detail-thumb ${index === activeImageIndex ? 'active' : ''}`;
    button.style.backgroundImage = `url(${src})`;
    button.addEventListener('click', () => {
      activeImageIndex = index;
      detailHeroImageEl.src = src;
      renderDetailGallery(images);
    });
    detailThumbsEl.appendChild(button);
  });
}

function cycleDetailImage(direction) {
  if (!activeProductId) return;
  const product = productCatalog[activeProductId];
  if (!product) return;
  activeImageIndex = (activeImageIndex + direction + product.images.length) % product.images.length;
  detailHeroImageEl.src = product.images[activeImageIndex];
  renderDetailGallery(product.images);
}

function closeDetailPanel() {
  if (!detailPanelEl) return;
  detailPanelEl.classList.remove('visible');
  document.body.classList.remove('detail-open');
  activeProductId = '';
}

function goToProduct(event, productId) {
  if (event && typeof event.preventDefault === 'function') {
    event.preventDefault();
  }
  if (!productId) return;
  window.location.href = `product.html?product=${productId}`;
}

if (detailPanelEl) {
  detailPanelEl.addEventListener('click', (event) => {
    if (event.target === detailPanelEl) {
      closeDetailPanel();
    }
  });
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
  const modal = document.getElementById('quoteModal');
  const result = document.getElementById('quoteResult');
  if (!modal) return;
  modal.style.display = 'block';
  if (result) {
    result.textContent = '';
  }
}

function openPaperWeightModal() {
  const modal = document.getElementById('paperWeightModal');
  const result = document.getElementById('paperWeightResult');
  if (!modal) return;
  modal.style.display = 'block';
  if (result) {
    result.textContent = '';
  }
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
  const inputEl = document.getElementById('productSearch');
  if (!inputEl) return;
  const query = inputEl.value.toLowerCase();
  const products = document.getElementsByClassName('product-card');
  let visibleCount = 0;

  for (let product of products) {
    const titleEl = product.querySelector('.product-card-title');
    const productId = product.getAttribute('data-product');
    const catalogEntry = productCatalog[productId] || {};
    const title = titleEl ? titleEl.textContent.toLowerCase() : '';
    const haystack = [
      title,
      catalogEntry.name,
      catalogEntry.tagline,
      catalogEntry.description,
      (catalogEntry.features || []).join(' '),
      catalogEntry.price
    ]
      .filter(Boolean)
      .join(' ')
      .toLowerCase();

    const isVisible = haystack.includes(query);
    product.style.display = isVisible ? '' : 'none';
    if (isVisible) visibleCount++;
  }

  const messageEl = document.getElementById('noResultsMessage');
  if (messageEl) {
    messageEl.style.display = visibleCount === 0 ? 'block' : 'none';
  }
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
  if (event.target.classList && event.target.classList.contains('modal')) closeModal();
  if (event.target === detailPanelEl) closeDetailPanel();
};

window.addEventListener('keydown', (e) => {
  if (e.key === "Escape") {
    closeModal();
    closeDetailPanel();
  }
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

function hydrateProductCards() {
  const grid = document.getElementById('productGrid');
  if (!grid) return;
  const fragment = document.createDocumentFragment();
  const entries = Object.entries(productCatalog);
  const priorityThreshold = 4;
  let cardIndex = 0;

  entries.forEach(([id, product]) => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.dataset.product = id;
    card.setAttribute('role', 'article');
    const isPriorityCard = cardIndex < priorityThreshold;
    cardIndex += 1;

    const carousel = document.createElement('div');
    carousel.className = 'image-carousel';
    carousel.tabIndex = 0;
    carousel.setAttribute('role', 'button');
    carousel.setAttribute('aria-label', `Open ${product.name} details`);
    carousel.addEventListener('click', (event) => goToProduct(event, id));
    carousel.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        goToProduct(event, id);
      }
    });

    const leftArrow = document.createElement('button');
    leftArrow.className = 'arrow left-arrow';
    leftArrow.innerHTML = '&#10094;';
    leftArrow.setAttribute('aria-label', 'Previous image');
    leftArrow.addEventListener('click', function(event) {
      event.stopPropagation();
      changeImage(this, -1);
    });
    carousel.appendChild(leftArrow);

    (product.images || []).forEach((src, index) => {
      const img = document.createElement('img');
      prepareCarouselImage({
        img,
        src,
        productName: product.name,
        imageIndex: index,
        priority: isPriorityCard && index === 0
      });
      carousel.appendChild(img);
    });

    const rightArrow = document.createElement('button');
    rightArrow.className = 'arrow right-arrow';
    rightArrow.innerHTML = '&#10095;';
    rightArrow.setAttribute('aria-label', 'Next image');
    rightArrow.addEventListener('click', function(event) {
      event.stopPropagation();
      changeImage(this, 1);
    });
    carousel.appendChild(rightArrow);

    card.appendChild(carousel);

    const titleButton = document.createElement('button');
    titleButton.type = 'button';
    titleButton.className = 'product-card-title';
    titleButton.innerHTML = `${product.name}<span class="hint-pill">${product.tagline}</span>`;
    titleButton.addEventListener('click', (event) => goToProduct(event, id));
    card.appendChild(titleButton);

    const summary = document.createElement('p');
    summary.className = 'product-card-summary';
    const description = product.description || '';
    summary.textContent = description.length > 140 ? `${description.slice(0, 137)}…` : description;
    summary.tabIndex = 0;
    summary.setAttribute('role', 'link');
    summary.addEventListener('click', (event) => goToProduct(event, id));
    summary.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        goToProduct(event, id);
      }
    });
    card.appendChild(summary);

    const price = document.createElement('p');
    price.className = 'product-price';
    price.textContent = product.price || '';
    card.appendChild(price);

    const actions = document.createElement('div');
    actions.className = 'product-card-actions';

    const quickView = document.createElement('button');
    quickView.type = 'button';
    quickView.className = 'secondary-cta';
    quickView.textContent = 'Quick specs';
    quickView.addEventListener('click', (event) => {
      event.stopPropagation();
      openProductDetail(id);
    });

    const enquire = document.createElement('button');
    enquire.type = 'button';
    enquire.className = 'primary-inline';
    enquire.textContent = 'Get Best Price';
    enquire.addEventListener('click', (event) => {
      event.stopPropagation();
      openEnquiry(product.name);
    });

    actions.appendChild(quickView);
    actions.appendChild(enquire);
    card.appendChild(actions);

    fragment.appendChild(card);
  });

  grid.innerHTML = '';
  grid.appendChild(fragment);

  requestAnimationFrame(() => {
    observeProductCards();
    queueDeferredImageLoads();
  });
}

function prepareCarouselImage({ img, src, productName, imageIndex, priority }) {
  img.className = 'carousel-image';
  img.alt = `${productName} preview ${imageIndex + 1}`;
  img.decoding = 'async';
  img.style.display = imageIndex === 0 ? 'block' : 'none';
  if (priority) {
    img.loading = 'eager';
    img.fetchPriority = 'high';
    assignImageSource(img, src, true);
  } else {
    img.loading = 'lazy';
    img.dataset.src = src;
  }
}

function assignImageSource(img, src, priority = false) {
  if (!img || !src) return;
  if (img.dataset.currentSrc === src) return;
  img.dataset.currentSrc = src;
  if (priority) {
    img.loading = 'eager';
    img.fetchPriority = 'high';
  }
  img.src = src;
  const markLoaded = () => {
    img.dataset.loaded = 'true';
  };
  const handleError = () => {
    img.dataset.loaded = 'error';
    delete img.dataset.currentSrc;
  };
  if (typeof img.decode === 'function') {
    img.decode().then(markLoaded).catch(markLoaded);
    img.addEventListener('error', handleError, { once: true });
  } else {
    img.addEventListener('load', markLoaded, { once: true });
    img.addEventListener('error', handleError, { once: true });
  }
  delete img.dataset.src;
}

function observeProductCards() {
  const cards = document.querySelectorAll('.product-card');
  if (!cards.length) return;

  const revealCard = (card) => {
    card.dataset.visible = 'true';
    const pendingImages = card.querySelectorAll('.carousel-image[data-src]');
    pendingImages.forEach(img => assignImageSource(img, img.dataset.src));
  };

  if (!('IntersectionObserver' in window)) {
    cards.forEach(revealCard);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        revealCard(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { rootMargin: '140px 0px' });

  cards.forEach(card => observer.observe(card));
}

function queueDeferredImageLoads() {
  const pending = Array.from(document.querySelectorAll('.carousel-image[data-src]'));
  if (!pending.length) return;
  const loadBatch = () => {
    pending.splice(0, 3).forEach(img => assignImageSource(img, img.dataset.src));
    if (pending.length) {
      setTimeout(loadBatch, 900);
    }
  };

  const schedule = () => {
    if (!pending.length) return;
    loadBatch();
  };

  if ('requestIdleCallback' in window) {
    requestIdleCallback(schedule, { timeout: 4500 });
  } else {
    setTimeout(schedule, 3500);
  }
}

let standaloneImageIndex = 0;

function renderStandaloneGallery(images = []) {
  const heroImageEl = document.getElementById('productPageHeroImage');
  const thumbsEl = document.getElementById('productPageThumbs');
  if (!heroImageEl || !thumbsEl) return;
  const safeImages = images.length ? images : ['images/background/image1.webp'];
  const imageToShow = safeImages[standaloneImageIndex] || safeImages[0];
  heroImageEl.src = imageToShow;
  thumbsEl.innerHTML = '';
  safeImages.forEach((src, index) => {
    const button = document.createElement('button');
    button.className = `detail-thumb ${index === standaloneImageIndex ? 'active' : ''}`;
    button.style.backgroundImage = `url(${src})`;
    button.addEventListener('click', () => {
      standaloneImageIndex = index;
      heroImageEl.src = src;
      renderStandaloneGallery(safeImages);
    });
    thumbsEl.appendChild(button);
  });
  if (!thumbsEl.dataset.prefetched) {
    schedulePreload(safeImages.slice(1));
    thumbsEl.dataset.prefetched = 'true';
  }
}

function initStandaloneProductPage() {
  const root = document.getElementById('productPageRoot');
  if (!root) return;

  const params = new URLSearchParams(window.location.search);
  const productId = params.get('product');
  const product = productCatalog[productId];
  const fallback = document.getElementById('productPageFallback');

  if (!product) {
    if (fallback) {
      fallback.style.display = 'flex';
    }
    root.style.display = 'none';
    return;
  }

  root.style.display = 'grid';
  if (fallback) {
    fallback.style.display = 'none';
  }

  document.title = `${product.name} | Jyoti Enterprises`;
  standaloneImageIndex = 0;
  const thumbsEl = document.getElementById('productPageThumbs');
  if (thumbsEl && thumbsEl.dataset.prefetched) {
    delete thumbsEl.dataset.prefetched;
  }
  renderStandaloneGallery(product.images);

  const mapping = [
    ['productPageBreadcrumb', product.name],
    ['productPageTagline', product.tagline],
    ['productPageTitle', product.name],
    ['productPageDescription', product.description],
    ['productPageSize', product.specs.size],
    ['productPageFinish', product.specs.finish],
    ['productPageLead', product.specs.lead],
    ['productPagePrice', product.price]
  ];

  mapping.forEach(([id, value]) => {
    const el = document.getElementById(id);
    if (el && value) {
      el.textContent = value;
    }
  });

  const featuresEl = document.getElementById('productPageFeatures');
  if (featuresEl) {
    featuresEl.innerHTML = (product.features || [])
      .map(feature => `<li>${feature}</li>`)
      .join('');
  }

  const primaryCta = document.getElementById('productPagePrimaryCta');
  if (primaryCta) {
    primaryCta.onclick = () => openEnquiry(product.name);
  }

  const whatsappCta = document.getElementById('productPageWhatsappCta');
  if (whatsappCta) {
    whatsappCta.onclick = () => {
      const encoded = encodeURIComponent(product.whatsapp);
      window.open(`https://wa.me/917032174014?text=${encoded}`, '_blank');
    };
  }
}
function preloadImages(imgArray) {
  imgArray.forEach(src => {
    const img = new Image();
    img.src = src;
  });
}

function schedulePreload(images = []) {
  if (!images.length) return;
  const runner = () => preloadImages(images);
  if ('requestIdleCallback' in window) {
    requestIdleCallback(runner, { timeout: 2500 });
  } else {
    setTimeout(runner, 1200);
  }
}

function setupHeroCanvas() {
  if (!heroCanvasEl || typeof heroCanvasEl.getContext !== 'function') return null;
  const ctx = heroCanvasEl.getContext('2d');
  if (!ctx) return null;
  let width = 0;
  let height = 0;
  let frameId;

  const resize = () => {
    const bounds = heroCanvasEl.getBoundingClientRect();
    width = bounds.width;
    height = bounds.height;
    const dpr = window.devicePixelRatio || 1;
    heroCanvasEl.width = Math.max(1, width * dpr);
    heroCanvasEl.height = Math.max(1, height * dpr);
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.scale(dpr, dpr);
  };

  const render = (time = 0) => {
    ctx.clearRect(0, 0, width, height);
    const layers = 12;
    const waveTime = time * 0.00035;
    for (let layer = 0; layer < layers; layer += 1) {
      const depth = layer / layers;
      const alpha = 0.08 + depth * 0.25;
      const amplitude = 18 - depth * 8;
      const offsetY = height * 0.7 - depth * 180;
      const spacing = Math.max(60, width / 12);
      ctx.beginPath();
      for (let x = -2; x <= 2; x += 0.2) {
        const wave = Math.sin((x + depth) * 2 + waveTime * 4) * amplitude;
        const projectX = width / 2 + x * spacing * (1 + depth * 0.6);
        const projectY = offsetY + wave;
        if (x === -2) {
          ctx.moveTo(projectX, projectY);
        } else {
          ctx.lineTo(projectX, projectY);
        }
      }
      ctx.strokeStyle = `rgba(46, 245, 255, ${alpha.toFixed(3)})`;
      ctx.lineWidth = 1;
      ctx.stroke();
    }
    frameId = requestAnimationFrame(render);
  };

  resize();
  frameId = requestAnimationFrame(render);
  window.addEventListener('resize', resize);

  return () => {
    cancelAnimationFrame(frameId);
    window.removeEventListener('resize', resize);
    ctx.clearRect(0, 0, width, height);
  };
}

function initParallaxScene() {
  if (!heroBackgroundEl) return null;
  const targets = document.querySelectorAll('[data-parallax]');
  if (!targets.length) return null;
  let pointerX = 0;
  let pointerY = 0;
  let pointerFrame = null;
  let autoDriftId = null;

  const applyTransforms = () => {
    targets.forEach(node => {
      const depth = parseFloat(node.dataset.depth || '0.1');
      const translateX = pointerX * depth * 20;
      const translateY = pointerY * depth * 12;
      node.style.transform = `translate3d(${translateX}px, ${translateY}px, 0)`;
    });
  };

  const schedule = () => {
    if (pointerFrame !== null) return;
    pointerFrame = requestAnimationFrame(() => {
      pointerFrame = null;
      applyTransforms();
    });
  };

  const updatePointer = (event) => {
    const bounds = heroBackgroundEl.getBoundingClientRect();
    if (!bounds.width || !bounds.height) return;
    pointerX = ((event.clientX - bounds.left) / bounds.width) - 0.5;
    pointerY = ((event.clientY - bounds.top) / bounds.height) - 0.5;
    schedule();
  };

  const resetPointer = () => {
    pointerX = 0;
    pointerY = 0;
    schedule();
  };

  const enablePointerTracking = !finePointerQuery || finePointerQuery.matches;
  let visibilityHandler = null;
  const drift = (time = 0) => {
    pointerX = Math.sin(time * 0.00025) * 0.35;
    pointerY = Math.cos(time * 0.00018) * 0.25;
    applyTransforms();
    autoDriftId = requestAnimationFrame(drift);
  };

  if (enablePointerTracking) {
    heroBackgroundEl.addEventListener('pointermove', updatePointer);
    heroBackgroundEl.addEventListener('pointerleave', resetPointer);
    window.addEventListener('blur', resetPointer);
  } else {
    autoDriftId = requestAnimationFrame(drift);
    visibilityHandler = () => {
      if (document.hidden && autoDriftId !== null) {
        cancelAnimationFrame(autoDriftId);
        autoDriftId = null;
      } else if (!document.hidden && autoDriftId === null) {
        autoDriftId = requestAnimationFrame(drift);
      }
    };
    document.addEventListener('visibilitychange', visibilityHandler);
  }

  schedule();

  return () => {
    heroBackgroundEl.removeEventListener('pointermove', updatePointer);
    heroBackgroundEl.removeEventListener('pointerleave', resetPointer);
    window.removeEventListener('blur', resetPointer);
    if (pointerFrame !== null) {
      cancelAnimationFrame(pointerFrame);
      pointerFrame = null;
    }
    if (autoDriftId !== null) {
      cancelAnimationFrame(autoDriftId);
      autoDriftId = null;
    }
    if (visibilityHandler) {
      document.removeEventListener('visibilitychange', visibilityHandler);
    }
    targets.forEach(node => {
      node.style.transform = '';
    });
  };
}

function bootstrapMotionSystems() {
  if (typeof teardownCanvasAnimation === 'function') {
    teardownCanvasAnimation();
    teardownCanvasAnimation = null;
  }
  if (typeof teardownParallaxScene === 'function') {
    teardownParallaxScene();
    teardownParallaxScene = null;
  }

  if (motionPreferenceQuery && motionPreferenceQuery.matches) {
    return;
  }

  teardownCanvasAnimation = setupHeroCanvas();
  teardownParallaxScene = initParallaxScene();
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
      document.getElementById("enquiryName").value = "";
      document.getElementById("enquiryEmail").value = "";
      document.getElementById("enquiryPhone").value = "";
      document.getElementById("enquiryProduct").value = "";
      document.getElementById("enquiryMessage").value = "";

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
  'images/background/image1.webp'
];

let bgIndex = 0;

function cycleBackground() {
  if (!heroBackgroundEl) return;
  heroBackgroundEl.style.backgroundImage = `url(${bgImages[bgIndex]})`;
  bgIndex = (bgIndex + 1) % bgImages.length;
}

cycleBackground();
if (bgImages.length > 1) {
  setInterval(cycleBackground, 5000);
}


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

hydrateProductCards();
const searchInputEl = document.getElementById('productSearch');
if (searchInputEl) {
  searchInputEl.addEventListener('input', filterProducts);
  filterProducts();
}
initStandaloneProductPage();
bootstrapMotionSystems();

if (motionPreferenceQuery) {
  const motionPreferenceHandler = () => bootstrapMotionSystems();
  if (typeof motionPreferenceQuery.addEventListener === 'function') {
    motionPreferenceQuery.addEventListener('change', motionPreferenceHandler);
  } else if (typeof motionPreferenceQuery.addListener === 'function') {
    motionPreferenceQuery.addListener(motionPreferenceHandler);
  }
}

