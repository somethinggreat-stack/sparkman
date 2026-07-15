/* ============================================================
   Sparkman Solutions — interactions
   ============================================================ */
(function () {
  "use strict";

  /* ---- Loader (with hard fallback so it can never get stuck) ---- */
  function hideLoader() {
    var loader = document.getElementById("loader");
    if (loader) loader.classList.add("hide");
  }
  window.addEventListener("load", function () { setTimeout(hideLoader, 450); });
  setTimeout(hideLoader, 2200);
  document.addEventListener("DOMContentLoaded", function () { setTimeout(hideLoader, 1400); });

  /* ---- Year ---- */
  var yr = document.getElementById("year");
  if (yr) yr.textContent = new Date().getFullYear();

  /* ---- Nav scroll state + progress + FAB ---- */
  var nav = document.getElementById("nav");
  var progress = document.getElementById("scrollProgress");
  var fab = document.getElementById("fab");

  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    if (nav) nav.classList.toggle("scrolled", y > 40);
    if (fab) fab.classList.toggle("show", y > 700);
    if (progress) {
      var h = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width = (h > 0 ? (y / h) * 100 : 0) + "%";
    }
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  /* ---- Mobile menu ---- */
  var toggle = document.getElementById("navToggle");
  var links = document.getElementById("navLinks");
  function closeMenu() {
    if (links) links.classList.remove("open");
    if (toggle) toggle.classList.remove("active");
  }
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      links.classList.toggle("open");
      toggle.classList.toggle("active");
    });
    links.querySelectorAll("a").forEach(function (a) {
      a.addEventListener("click", closeMenu);
    });
  }

  /* ---- Reveal on scroll ---- */
  var reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add("in");
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    reveals.forEach(function (el, i) {
      el.style.transitionDelay = (i % 4) * 70 + "ms";
      io.observe(el);
    });
  } else {
    reveals.forEach(function (el) { el.classList.add("in"); });
  }

  /* ---- Animated counters ---- */
  var counters = document.querySelectorAll("[data-count]");
  function animateCount(el) {
    var target = parseInt(el.getAttribute("data-count"), 10);
    var dur = 1600, start = null;
    function tick(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var val = Math.floor(eased * target);
      el.textContent = val >= 1000 ? val.toLocaleString() : String(val);
      if (p < 1) requestAnimationFrame(tick);
      else el.textContent = target >= 1000 ? target.toLocaleString() : String(target);
    }
    requestAnimationFrame(tick);
  }
  if ("IntersectionObserver" in window && counters.length) {
    var co = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { animateCount(e.target); co.unobserve(e.target); }
      });
    }, { threshold: 0.6 });
    counters.forEach(function (c) { co.observe(c); });
  }

  /* ---- Hero live credit-score gauge ---- */
  var heroScore = document.getElementById("heroScore");
  var gaugeArc = document.getElementById("gaugeArc");
  var gaugeDot = document.getElementById("gaugeDot");
  var heroTier = document.getElementById("heroTier");
  var heroDelta = document.getElementById("heroDelta");
  if (heroScore && gaugeArc) {
    var ARC_LEN = 396;         // path length (semicircle r=126)
    var CX = 150, CY = 156, R = 126;
    var SMIN = 300, SMAX = 850;
    var FROM = 540, TO = 812;

    function tierFor(s) {
      if (s < 580) return "Poor";
      if (s < 670) return "Fair";
      if (s < 740) return "Good";
      if (s < 800) return "Very Good";
      return "Excellent";
    }
    function render(score) {
      var f = (score - SMIN) / (SMAX - SMIN);
      f = Math.max(0, Math.min(1, f));
      gaugeArc.style.strokeDashoffset = ARC_LEN * (1 - f);
      var theta = Math.PI * (1 - f);           // 180deg -> 0deg
      gaugeDot.setAttribute("cx", CX + R * Math.cos(theta));
      gaugeDot.setAttribute("cy", CY - R * Math.sin(theta));
      heroScore.textContent = Math.round(score);
      if (heroTier) heroTier.textContent = tierFor(score);
      if (heroDelta) heroDelta.textContent = "+" + Math.max(0, Math.round(score - FROM));
    }
    render(FROM);

    var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduce) { render(TO); }

    var played = reduce;
    var ho = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting && !played) {
          played = true;
          var start = null, dur = 2200;
          function step(ts) {
            if (!start) start = ts + 350;       // brief hold on the "before" score
            var p = Math.max(0, Math.min((ts - start) / dur, 1));
            var eased = p < 0.5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
            render(FROM + (TO - FROM) * eased);
            if (p < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
        }
      });
    }, { threshold: 0.4 });
    ho.observe(document.querySelector(".gauge-card") || heroScore);
  }

  /* ---- FAQ: close others on open (accordion) ---- */
  var faqItems = document.querySelectorAll(".faq__item");
  faqItems.forEach(function (item) {
    item.addEventListener("toggle", function () {
      if (item.open) {
        faqItems.forEach(function (other) {
          if (other !== item) other.open = false;
        });
      }
    });
  });

  /* ---- Lead forms (modal + any inline form) ---- */
  var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  function resetForm(form) {
    var success = form.querySelector(".form__success");
    form.reset();
    form.querySelectorAll(".field").forEach(function (f) { f.style.display = ""; });
    var sb = form.querySelector("button[type=submit]");
    if (sb) { sb.style.display = ""; sb.disabled = false; sb.textContent = sb.getAttribute("data-label") || "Submit"; }
    var disc = form.querySelector(".form__disclaimer");
    if (disc) disc.style.display = "";
    if (success) success.hidden = true;
  }
  document.querySelectorAll("form.lead-form").forEach(function (form) {
    var submitBtn = form.querySelector("button[type=submit]");
    if (submitBtn && !submitBtn.getAttribute("data-label")) {
      submitBtn.setAttribute("data-label", submitBtn.textContent.trim());
    }
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var valid = true;
      form.querySelectorAll("[required]").forEach(function (field) {
        var ok = field.value.trim() !== "";
        if (field.type === "email") ok = emailRe.test(field.value);
        field.classList.toggle("invalid", !ok);
        if (!ok) valid = false;
      });
      if (!valid) return;
      var btn = form.querySelector("button[type=submit]");
      var success = form.querySelector(".form__success");
      if (btn) { btn.textContent = "Sending…"; btn.disabled = true; }

      var data = new FormData(form);
      var leadType = form.getAttribute("data-lead-type") ||
        (form.id === "fundingForm" ? "funding" : (form.id === "leadForm" ? "popup" : "credit"));
      data.append("type", leadType);
      data.append("source_url", window.location.pathname);
      var tokenEl = document.querySelector('meta[name="csrf-token"]');

      fetch("/lead", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": tokenEl ? tokenEl.getAttribute("content") : "",
          "X-Requested-With": "XMLHttpRequest",
          "Accept": "application/json"
        },
        body: data
      }).then(function (r) {
        if (!r.ok) throw new Error("Request failed");
        return r.json();
      }).then(function () {
        form.querySelectorAll(".field").forEach(function (f) { f.style.display = "none"; });
        if (btn) btn.style.display = "none";
        var disc = form.querySelector(".form__disclaimer");
        if (disc) disc.style.display = "none";
        if (success) success.hidden = false;
      }).catch(function () {
        if (btn) { btn.disabled = false; btn.textContent = btn.getAttribute("data-label") || "Submit"; }
        alert("Sorry — something went wrong sending your request. Please call or text us instead.");
      });
    });
    form.querySelectorAll("input,select,textarea").forEach(function (f) {
      f.addEventListener("input", function () { f.classList.remove("invalid"); });
    });
  });

  /* ---- Lead capture → funding qualifier funnel ----
     All lead CTAs (Get My Free Analysis, Free Consultation, Free Analysis FAB,
     footer Free Analysis, any [data-open-form] or #contact link) now route to
     the qualifier at /get-funded — the single lead-capture form on the site. */
  document.querySelectorAll('[data-open-form], a[href="#contact"], a[href="/#contact"]').forEach(function (el) {
    el.addEventListener("click", function (e) {
      e.preventDefault();
      window.location.href = "/get-funded";
    });
  });
})();
