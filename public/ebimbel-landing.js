(function () {
  "use strict";

  var prefersReduced =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* Scroll reveal */
  var revealEls = document.querySelectorAll(".eb-reveal");
  if (revealEls.length && !prefersReduced) {
    var revealObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("eb-reveal--visible");
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
    );
    revealEls.forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    revealEls.forEach(function (el) {
      el.classList.add("eb-reveal--visible");
    });
  }

  /* Navbar shadow on scroll */
  var header = document.querySelector(".eb-land-header");
  if (header) {
    var onScroll = function () {
      header.classList.toggle("eb-land-header--scrolled", window.scrollY > 24);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* Animated counters */
  var counters = document.querySelectorAll("[data-count]");
  if (counters.length && !prefersReduced) {
    var counterObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var el = entry.target;
          var target = parseInt(el.getAttribute("data-count"), 10);
          var suffix = el.getAttribute("data-suffix") || "";
          var duration = 1400;
          var start = performance.now();
          var tick = function (now) {
            var p = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased) + suffix;
            if (p < 1) requestAnimationFrame(tick);
          };
          requestAnimationFrame(tick);
          counterObserver.unobserve(el);
        });
      },
      { threshold: 0.5 }
    );
    counters.forEach(function (c) {
      counterObserver.observe(c);
    });
  }

  /* Franchise step highlight on scroll */
  var steps = document.querySelectorAll(".eb-land-step");
  if (steps.length && !prefersReduced) {
    var stepObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) entry.target.classList.add("eb-land-step--active");
        });
      },
      { threshold: 0.6 }
    );
    steps.forEach(function (s) {
      stepObserver.observe(s);
    });
  }
})();
