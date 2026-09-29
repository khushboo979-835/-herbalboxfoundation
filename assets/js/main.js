/**
 * NGO Seva Foundation - Master Frontend JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
  // Counter Animation for Impact Statistics
  const counters = document.querySelectorAll('.counter-value');
  if (counters.length > 0) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counter = entry.target;
          const target = parseInt(counter.getAttribute('data-target') || '0', 10);
          const duration = 1500;
          const stepTime = 20;
          const steps = duration / stepTime;
          const increment = target / steps;
          let current = 0;

          const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
              counter.textContent = target.toLocaleString('en-IN');
              clearInterval(timer);
            } else {
              counter.textContent = Math.floor(current).toLocaleString('en-IN');
            }
          }, stepTime);

          observer.unobserve(counter);
        }
      });
    }, { threshold: 0.2 });

    counters.forEach(c => counterObserver.observe(c));
  }

  // Back to Top Button
  const backToTopBtn = document.getElementById('backToTop');
  if (backToTopBtn) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTopBtn.style.display = 'flex';
      } else {
        backToTopBtn.style.display = 'none';
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Interactive Donation Amount Selection
  const amountButtons = document.querySelectorAll('.donation-amount-btn');
  const customAmountInput = document.getElementById('customAmountInput');
  const finalAmountField = document.getElementById('finalDonationAmount');

  if (amountButtons.length > 0 && finalAmountField) {
    amountButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        amountButtons.forEach(b => b.classList.remove('active', 'btn-primary'));
        amountButtons.forEach(b => b.classList.add('btn-outline-primary'));
        
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('active', 'btn-primary');

        const val = btn.getAttribute('data-amount');
        if (val !== 'custom') {
          finalAmountField.value = val;
          if (customAmountInput) customAmountInput.value = '';
        } else {
          if (customAmountInput) {
            customAmountInput.focus();
            finalAmountField.value = customAmountInput.value || '1000';
          }
        }
      });
    });

    if (customAmountInput) {
      customAmountInput.addEventListener('input', (e) => {
        amountButtons.forEach(b => b.classList.remove('active', 'btn-primary'));
        amountButtons.forEach(b => b.classList.add('btn-outline-primary'));
        finalAmountField.value = e.target.value;
      });
    }
  }

  // Gallery Category Filter
  const galleryFilterBtns = document.querySelectorAll('.gallery-filter-btn');
  const galleryItems = document.querySelectorAll('.gallery-grid-item');

  if (galleryFilterBtns.length > 0 && galleryItems.length > 0) {
    galleryFilterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        galleryFilterBtns.forEach(b => b.classList.remove('active', 'btn-ngo-primary'));
        galleryFilterBtns.forEach(b => b.classList.add('btn-outline-secondary'));

        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('active', 'btn-ngo-primary');

        const category = btn.getAttribute('data-filter');

        galleryItems.forEach(item => {
          if (category === 'all' || item.getAttribute('data-category') === category) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }
});
