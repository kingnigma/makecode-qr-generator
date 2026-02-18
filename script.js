// Global state management
let currentStep = 1;
let currentMode = "qr"; // Track current mode: "qr" or "bar"
let selectedDataType = "";
let qrCodeData = {
  type: "",
  content: "",
  logo: null,
  centerText: "",
  centerTextColor: "#000000",
  updateLater: false,
  dynamicTracking: false,
  size: 400,
  dpi: 72,
};

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  initializeEventListeners();
  initializeMobileMenu();
});

// Mobile Menu Toggle
function initializeMobileMenu() {
  const mobileMenuToggle = document.getElementById("mobileMenuToggle");
  const navLinks = document.getElementById("navLinks");

  if (mobileMenuToggle && navLinks) {
    mobileMenuToggle.addEventListener("click", function () {
      navLinks.classList.toggle("active");
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (e) {
      if (
        !mobileMenuToggle.contains(e.target) &&
        !navLinks.contains(e.target)
      ) {
        navLinks.classList.remove("active");
      }
    });

    // Close menu when clicking a link
    const navLinksItems = navLinks.querySelectorAll(".nav-link, .btn");
    navLinksItems.forEach((link) => {
      link.addEventListener("click", function () {
        navLinks.classList.remove("active");
      });
    });
  }
}

// Initialize all event listeners
function initializeEventListeners() {
  // Tab switching (QR Code / Bar Code)
  const tabs = document.querySelectorAll(".tab");
  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("tab-active"));
      this.classList.add("tab-active");
      const tabType = this.getAttribute("data-tab");
      currentMode = tabType; // Update current mode
      toggleMainTab(tabType);
      console.log("Switched to:", tabType);
    });
  });

  // Toggle QR <-> Barcode UI and preview
  function toggleMainTab(tabType) {
    const qrModeElements = document.querySelectorAll(".qr-mode");
    const barcodeModeElements = document.querySelectorAll(".barcode-mode");
    const qrPreview = document.getElementById("qrCode");
    const barcodePreview = document.getElementById("barcodePreview");

    if (tabType === "bar") {
      // Switch to BARCODE mode
      goToStep(1); // Always start at step 1

      // Hide all QR mode elements, show all barcode mode elements
      qrModeElements.forEach((el) => (el.style.display = "none"));
      barcodeModeElements.forEach((el) => (el.style.display = "block"));

      // Switch preview panels
      if (qrPreview) qrPreview.style.display = "none";
      if (barcodePreview) barcodePreview.style.display = "block";
    } else {
      // Switch to QR CODE mode
      goToStep(1);

      // Show all QR mode elements, hide all barcode mode elements
      qrModeElements.forEach((el) => (el.style.display = "block"));
      barcodeModeElements.forEach((el) => (el.style.display = "none"));

      // Switch preview panels
      if (qrPreview) qrPreview.style.display = "block";
      if (barcodePreview) barcodePreview.style.display = "none";
    }
  }

  // Data Type Selection (QR CODE only)
  const dataTypeButtons = document.querySelectorAll(".data-type-btn");
  dataTypeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      dataTypeButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      selectedDataType = this.getAttribute("data-type");
      qrCodeData.type = selectedDataType;
      console.log("Selected data type:", selectedDataType);

      // show corresponding destination block
      const destBlocks = document.querySelectorAll(".dest-block");
      destBlocks.forEach((b) => (b.style.display = "none"));
      const show = document.getElementById("dest-" + selectedDataType);
      if (show) show.style.display = "block";

      // Automatically move to step 2 after selecting data type
      setTimeout(() => {
        goToStep(2);
      }, 300);
    });
  });

  // Step 2: Style Your QR Code button
  const styleYourQRBtn = document.getElementById("styleYourQRBtn");
  if (styleYourQRBtn) {
    styleYourQRBtn.addEventListener("click", function () {
      // Collect data based on selectedDataType
      let content = "";
      switch (selectedDataType) {
        case "url": {
          const urlInput = document.getElementById("urlInput");
          const url = urlInput ? urlInput.value.trim() : "";
          if (!url) {
            alert("Please enter a URL");
            return;
          }
          if (!isValidURL(url)) {
            alert("Please enter a valid URL (e.g., https://example.com)");
            return;
          }
          content = url;
          break;
        }
        case "email": {
          const to = (document.getElementById("emailTo") || {}).value || "";
          const cc = (document.getElementById("emailCc") || {}).value || "";
          const bcc = (document.getElementById("emailBcc") || {}).value || "";
          let subject =
            (document.getElementById("emailSubject") || {}).value || "";
          let body = (document.getElementById("emailBody") || {}).value || "";
          // enforce limits
          subject = subject.substring(0, 200);
          body = body.substring(0, 1000);
          if (!to) {
            alert("Please enter recipient email address");
            return;
          }
          const params = [];
          if (cc) params.push("cc=" + encodeURIComponent(cc));
          if (bcc) params.push("bcc=" + encodeURIComponent(bcc));
          if (subject) params.push("subject=" + encodeURIComponent(subject));
          if (body) params.push("body=" + encodeURIComponent(body));
          content =
            `mailto:${to}` + (params.length ? "?" + params.join("&") : "");
          break;
        }
        case "phone": {
          const phone =
            (document.getElementById("phoneNumber") || {}).value || "";
          if (!phone) {
            alert("Please enter a phone number");
            return;
          }
          content = `tel:${phone}`;
          break;
        }
        case "sms": {
          const phone =
            (document.getElementById("smsNumber") || {}).value || "";
          let msg = (document.getElementById("smsBody") || {}).value || "";
          msg = msg.substring(0, 1000);
          if (!phone) {
            alert("Please enter a phone number for SMS");
            return;
          }
          // Use SMS URI scheme
          content = `SMSTO:${phone}:${msg}`;
          break;
        }
        case "text": {
          let txt = (document.getElementById("plainText") || {}).value || "";
          if (!txt) {
            alert("Please enter the text content");
            return;
          }
          txt = txt.substring(0, 5000);
          content = txt;
          break;
        }
        case "location": {
          const loc =
            (document.getElementById("locationUrl") || {}).value || "";
          if (!loc) {
            alert("Please enter the Google Maps URL");
            return;
          }
          if (!isValidURL(loc)) {
            alert("Please enter a valid URL");
            return;
          }
          content = loc;
          break;
        }
        case "wifi": {
          const ssid = (document.getElementById("wifiSsid") || {}).value || "";
          const hidden =
            (document.getElementById("wifiHidden") || {}).value || "false";
          const pass =
            (document.getElementById("wifiPassword") || {}).value || "";
          const sec =
            (document.getElementById("wifiSecurity") || {}).value || "WPA";
          if (!ssid) {
            alert("Please enter SSID");
            return;
          }
          // WIFI:T:<WPA|WEP|nopass>;S:<ssid>;P:<password>;H:<true|false>;;
          content = `WIFI:T:${sec};S:${escapeSemicolons(ssid)};P:${escapeSemicolons(pass)};H:${hidden};;`;
          break;
        }
        case "social": {
          const sname =
            (document.getElementById("socialName") || {}).value || "";
          const surl = (document.getElementById("socialUrl") || {}).value || "";
          if (!surl) {
            alert("Please enter the social page URL");
            return;
          }
          if (!isValidURL(surl)) {
            alert("Please enter a valid URL");
            return;
          }
          // Store as plain URL but also keep name in metadata
          qrCodeData.socialName = sname;
          content = surl;
          break;
        }
        case "meet": {
          const m = (document.getElementById("meetUrl") || {}).value || "";
          if (!m) {
            alert("Please enter the Google Meet URL");
            return;
          }
          if (!isValidURL(m)) {
            alert("Please enter a valid URL");
            return;
          }
          content = m;
          break;
        }
        default: {
          alert("Please select a data type");
          return;
        }
      }

      qrCodeData.content = content;
      qrCodeData.type = selectedDataType;
      console.log("Prepared QR content:", content);
      goToStep(3);
    });
  }

  // helper: escape semicolons for WIFI fields
  function escapeSemicolons(s) {
    return s.replace(/;/g, "\\;");
  }

  // Step 3: Finish & Download button
  const finishDownloadBtn = document.getElementById("finishDownloadBtn");
  if (finishDownloadBtn) {
    finishDownloadBtn.addEventListener("click", function () {
      // Get style data
      const centerText = document.getElementById("centerText").value;
      const centerTextColor = document.getElementById("centerTextColor").value;

      qrCodeData.centerText = centerText;
      qrCodeData.centerTextColor = centerTextColor;

      // Generate QR code and move to step 4
      generateQRCode();
      goToStep(4);
    });
  }

  // Back buttons for QR CODE mode
  const backToDataType = document.getElementById("backToDataType");
  if (backToDataType) {
    backToDataType.addEventListener("click", () => goToStep(1));
  }

  const backToDestination = document.getElementById("backToDestination");
  if (backToDestination) {
    backToDestination.addEventListener("click", () => goToStep(2));
  }

  const backToStyle = document.getElementById("backToStyle");
  if (backToStyle) {
    backToStyle.addEventListener("click", () => goToStep(3));
  }

  // Back buttons for BARCODE mode
  const barcodeBackToDataType = document.getElementById(
    "barcodeBackToDataType",
  );
  if (barcodeBackToDataType) {
    barcodeBackToDataType.addEventListener("click", () => goToStep(1));
  }

  const backToBarcodeDestination = document.getElementById(
    "backToBarcodeDestination",
  );
  if (backToBarcodeDestination) {
    backToBarcodeDestination.addEventListener("click", () => goToStep(2));
  }

  const backToBarcodeStyle = document.getElementById("backToBarcodeStyle");
  if (backToBarcodeStyle) {
    backToBarcodeStyle.addEventListener("click", () => goToStep(3));
  }

  // Logo Upload
  const logoUpload = document.getElementById("logoUpload");
  if (logoUpload) {
    logoUpload.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          alert("File size must be less than 5MB");
          return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
          qrCodeData.logo = event.target.result;
          console.log("Logo uploaded");
          // show preview in the style upload area
          const preview = document.getElementById("logoUploadPreview");
          const icon = document.getElementById("styleUploadIcon");
          if (preview) {
            preview.src = event.target.result;
            preview.style.display = "block";
          }
          if (icon) {
            icon.style.opacity = "0.3";
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Center Text Color Picker
  const centerTextColor = document.getElementById("centerTextColor");
  const centerTextColorHex = document.getElementById("centerTextColorHex");

  if (centerTextColor && centerTextColorHex) {
    centerTextColor.addEventListener("input", function () {
      centerTextColorHex.value = this.value.toUpperCase();
    });

    centerTextColorHex.addEventListener("input", function () {
      if (/^#[0-9A-F]{6}$/i.test(this.value)) {
        centerTextColor.value = this.value;
      }
    });
  }

  // Checkboxes
  const updateLaterCheckbox = document.getElementById("updateLater");
  const dynamicTrackingCheckbox = document.getElementById("dynamicTracking");

  if (updateLaterCheckbox) {
    updateLaterCheckbox.addEventListener("change", function () {
      qrCodeData.updateLater = this.checked;
    });
  }

  if (dynamicTrackingCheckbox) {
    dynamicTrackingCheckbox.addEventListener("change", function () {
      qrCodeData.dynamicTracking = this.checked;
    });
  }

  // Download Settings Toggle
  const showCustomSettings = document.getElementById("showCustomSettings");
  if (showCustomSettings) {
    showCustomSettings.addEventListener("click", function () {
      document.getElementById("standardDownload").style.display = "none";
      document.getElementById("customDownload").style.display = "block";
    });
  }

  // ----------------- BARCODE specific listeners -----------------
  const barcodeSourceRadios = document.querySelectorAll(
    'input[name="barcodeSourceMode"]',
  );
  barcodeSourceRadios.forEach((r) => {
    r.addEventListener("change", function () {
      const val = this.value;
      document.getElementById("barcodeCounterMode").style.display =
        val === "counter" ? "block" : "none";
      document.getElementById("barcodeFileMode").style.display =
        val === "file" ? "block" : "none";
    });
  });

  const barcodeToDestinationBtn = document.getElementById(
    "barcodeToDestinationBtn",
  );
  if (barcodeToDestinationBtn) {
    barcodeToDestinationBtn.addEventListener("click", function () {
      const mode = document.querySelector(
        'input[name="barcodeSourceMode"]:checked',
      ).value;
      const sym = document.getElementById("barcodeSymbology").value;
      let previewList = [];

      if (mode === "counter") {
        const prefix = document.getElementById("barcodePrefix").value || "";
        const start = parseInt(
          document.getElementById("barcodeStart").value || "1",
          10,
        );
        const inc = parseInt(
          document.getElementById("barcodeIncrement").value || "1",
          10,
        );
        const count = parseInt(
          document.getElementById("barcodeCount").value || "1",
          10,
        );
        for (let i = 0; i < count; i++) {
          previewList.push(prefix + (start + i * inc));
        }
      } else {
        const fileInput = document.getElementById("barcodeDataFile");
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
          alert("Please upload a data file or switch to Counter mode");
          return;
        }
        const file = fileInput.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
          const lines = e.target.result
            .split(/\r?\n/)
            .map((line) => line.trim())
            .filter((line) => line.length > 0);
          previewList = lines;

          // Show preview and move to step 2
          const previewDiv = document.getElementById("barcodeDataPreview");
          if (previewList.length <= 50) {
            previewDiv.innerHTML = previewList.join("<br>");
          } else {
            previewDiv.innerHTML =
              previewList.slice(0, 50).join("<br>") +
              "<br>... and " +
              (previewList.length - 50) +
              " more.";
          }
          goToStep(2);
        };
        reader.readAsText(file);
        return; // Exit early since file reading is async
      }

      // For counter mode, show preview immediately
      const previewDiv = document.getElementById("barcodeDataPreview");
      if (previewList.length <= 50) {
        previewDiv.innerHTML = previewList.join("<br>");
      } else {
        previewDiv.innerHTML =
          previewList.slice(0, 50).join("<br>") +
          "<br>... and " +
          (previewList.length - 50) +
          " more.";
      }
      goToStep(2);
    });
  }

  const barcodePreviewSamplesBtn = document.getElementById(
    "barcodePreviewSamplesBtn",
  );
  if (barcodePreviewSamplesBtn) {
    barcodePreviewSamplesBtn.addEventListener("click", function () {
      const mode = document.querySelector(
        'input[name="barcodeSourceMode"]:checked',
      ).value;
      const sym = document.getElementById("barcodeSymbology").value;
      let sampleData = "";

      if (mode === "counter") {
        const prefix = document.getElementById("barcodePrefix").value || "";
        const start = parseInt(
          document.getElementById("barcodeStart").value || "1",
          10,
        );
        sampleData = prefix + start;
      } else {
        sampleData = "SAMPLE123";
      }

      // Render barcode preview using JsBarcode
      const canvas = document.createElement("canvas");
      const previewDiv = document.getElementById("barcodePreviewCanvas");
      previewDiv.innerHTML = "";
      previewDiv.appendChild(canvas);

      try {
        let barcodeType = sym.toUpperCase();
        if (barcodeType === "CODE128") barcodeType = "CODE128";
        if (barcodeType === "CODE39") barcodeType = "CODE39";
        if (barcodeType === "UPC-A") barcodeType = "UPC";
        if (barcodeType === "EAN13") barcodeType = "EAN13";

        JsBarcode(canvas, sampleData, {
          format: barcodeType,
          width: 2,
          height: 80,
          displayValue: true,
        });
      } catch (err) {
        console.error("Barcode preview error:", err);
        previewDiv.innerHTML = "Invalid barcode data or format";
      }
    });
  }

  const styleYourBarcodeBtn = document.getElementById("styleYourBarcodeBtn");
  if (styleYourBarcodeBtn) {
    styleYourBarcodeBtn.addEventListener("click", function () {
      goToStep(3);
    });
  }

  // Barcode size preset selector
  const barcodeSizePreset = document.getElementById("barcodeSizePreset");
  const customSizeInputs = document.getElementById("customSizeInputs");
  const barcodeWidth = document.getElementById("barcodeWidth");
  const barcodeHeight = document.getElementById("barcodeHeight");

  if (barcodeSizePreset) {
    barcodeSizePreset.addEventListener("change", function () {
      const preset = this.value;
      if (preset === "custom") {
        customSizeInputs.style.display = "block";
      } else {
        customSizeInputs.style.display = "none";
        // Set standard sizes based on preset
        if (preset === "standard") {
          barcodeWidth.value = 440;
          barcodeHeight.value = 306;
        } else if (preset === "small") {
          barcodeWidth.value = 295;
          barcodeHeight.value = 212;
        } else if (preset === "large") {
          barcodeWidth.value = 590;
          barcodeHeight.value = 413;
        } else if (preset === "basic_size") {
          barcodeWidth.value = 222;
          barcodeHeight.value = 122;
        }
      }
    });
  }

  let lastBarcodeResult = null;

  const finishGenerateBarBtn = document.getElementById("finishGenerateBarBtn");
  if (finishGenerateBarBtn) {
    finishGenerateBarBtn.addEventListener("click", function () {
      const projectName = document.getElementById("barcodeProjectName").value;
      const sym = document.getElementById("barcodeSymbology").value;
      const mode = document.querySelector(
        'input[name="barcodeSourceMode"]:checked',
      ).value;
      const width = parseInt(
        document.getElementById("barcodeWidth").value || "600",
        10,
      );
      const height = parseInt(
        document.getElementById("barcodeHeight").value || "120",
        10,
      );
      const fgColor = document.getElementById("barcodeFgColor").value;
      const bgColor = document.getElementById("barcodeBgColor").value;
      const fontSize = parseInt(
        document.getElementById("barcodeFontSize").value || "12",
        10,
      );
      const preventDup =
        document.getElementById("barcodePreventDup").checked || false;

      let dataList = [];
      if (mode === "counter") {
        const prefix = document.getElementById("barcodePrefix").value || "";
        const start = parseInt(
          document.getElementById("barcodeStart").value || "1",
          10,
        );
        const inc = parseInt(
          document.getElementById("barcodeIncrement").value || "1",
          10,
        );
        const count = parseInt(
          document.getElementById("barcodeCount").value || "1",
          10,
        );
        for (let i = 0; i < count; i++) {
          dataList.push(prefix + (start + i * inc));
        }
      } else {
        const previewDiv = document.getElementById("barcodeDataPreview");
        const html = previewDiv.innerHTML;
        dataList = html
          .split("<br>")
          .map((s) => s.trim())
          .filter((s) => s.length && !s.startsWith("..."));
      }

      // Send to server to generate barcode images
      const form = new FormData();
      form.append("projectName", projectName);
      form.append("symbology", sym);
      form.append("width", width);
      form.append("height", height);
      form.append("fgColor", fgColor);
      form.append("bgColor", bgColor);
      form.append("fontSize", fontSize);
      form.append("preventDuplicates", preventDup ? "1" : "0");

      if (mode === "counter") {
        // send counter parameters expected by the PHP endpoint
        form.append("mode", "counter");
        form.append(
          "prefix",
          document.getElementById("barcodePrefix").value || "",
        );
        form.append(
          "start",
          parseInt(document.getElementById("barcodeStart").value || "1", 10),
        );
        form.append(
          "increment",
          parseInt(
            document.getElementById("barcodeIncrement").value || "1",
            10,
          ),
        );
        form.append(
          "count",
          parseInt(document.getElementById("barcodeCount").value || "1", 10),
        );
      } else {
        // file mode: send JSON-encoded dataList so PHP can read it from $_POST['dataList']
        form.append("mode", "file");
        form.append("dataList", JSON.stringify(dataList));
      }

      fetch("generate-barcode.php", {
        method: "POST",
        body: form,
      })
        .then((res) => res.json())
        .then((resp) => {
          lastBarcodeResult = resp;
          // populate finish preview and download links
          const previewEl = document.getElementById("barcodeFinishPreview");
          previewEl.innerHTML = "";
          if (resp.generated && resp.generated.length) {
            resp.generated.forEach((g) => {
              const img = document.createElement("img");
              img.src = g.url;
              img.className = "barcode-thumb";
              img.alt = g.data || "";
              previewEl.appendChild(img);
            });
          }
          if (resp.zipUrl) {
            const zipLink = document.getElementById("downloadBarcodeZipLink");
            zipLink.href = resp.zipUrl;
            zipLink.style.display = "inline-block";
          }

          goToStep(4);
        })
        .catch((err) => {
          console.error(err);
          alert("Network or server error while generating barcodes");
        });
    });
  }

  const downloadBarcodePNG = document.getElementById("downloadBarcodePNG");
  if (downloadBarcodePNG) {
    downloadBarcodePNG.addEventListener("click", function () {
      if (!lastBarcodeResult) {
        alert("No generated barcode available to download");
        return;
      }

      // Prefer ZIP (batch) if available
      if (lastBarcodeResult.zipUrl) {
        const a = document.createElement("a");
        a.href = lastBarcodeResult.zipUrl;
        a.download = lastBarcodeResult.zipUrl.split("/").pop();
        document.body.appendChild(a);
        a.click();
        a.remove();
        return;
      }

      // Fallback to first PNG when ZIP not present
      if (lastBarcodeResult.generated && lastBarcodeResult.generated.length) {
        const url = lastBarcodeResult.generated[0].url;
        const a = document.createElement("a");
        a.href = url;
        a.download = url.split("/").pop();
        document.body.appendChild(a);
        a.click();
        a.remove();
        return;
      }

      alert("No generated barcode available to download");
    });
  }

  // -------------------------------------------------------------

  // Download Buttons
  const downloadJPEG = document.getElementById("downloadJPEG");
  const downloadPNG = document.getElementById("downloadPNG");
  const downloadJPEGCustom = document.getElementById("downloadJPEGCustom");
  const downloadPNGCustom = document.getElementById("downloadPNGCustom");

  if (downloadJPEG) {
    downloadJPEG.addEventListener("click", () => downloadQRCode("jpeg"));
  }
  if (downloadPNG) {
    downloadPNG.addEventListener("click", () => downloadQRCode("png"));
  }
  if (downloadJPEGCustom) {
    downloadJPEGCustom.addEventListener("click", () =>
      downloadQRCode("jpeg", true),
    );
  }
  if (downloadPNGCustom) {
    downloadPNGCustom.addEventListener("click", () =>
      downloadQRCode("png", true),
    );
  }

  // Form tabs (header navigation)
  const formTabs = document.querySelectorAll(".form-tab");
  formTabs.forEach((formTab) => {
    formTab.addEventListener("click", function () {
      const step = parseInt(this.getAttribute("data-step"));

      // Only allow navigation to completed steps
      if (step <= currentStep) {
        goToStep(step);
      }
    });
  });

  // Hero CTA buttons
  const startCreatingBtn = document.getElementById("startCreatingBtn");
  if (startCreatingBtn) {
    startCreatingBtn.addEventListener("click", function () {
      const generatorSection = document.querySelector(".generator-section");
      if (generatorSection) {
        generatorSection.scrollIntoView({ behavior: "smooth" });
      }
    });
  }

  // Ensure UI matches the currently active top tab on load
  const initialActiveTab = document.querySelector(".tab.tab-active");
  if (initialActiveTab) {
    toggleMainTab(initialActiveTab.getAttribute("data-tab") || "qr");
  }

  // Modal popup handlers
  const whatIsQRBtn = document.getElementById("whatIsQRBtn");
  const howToUseBtn = document.getElementById("howToUseBtn");
  const discoverFeaturesBtn = document.getElementById("discoverFeaturesBtn");
  const whatIsQRModal = document.getElementById("whatIsQRModal");
  const howToUseModal = document.getElementById("howToUseModal");
  const featuresModal = document.getElementById("featuresModal");
  const modalCloses = document.querySelectorAll(".modal-close");

  if (whatIsQRBtn) {
    whatIsQRBtn.addEventListener("click", function (e) {
      e.preventDefault();
      whatIsQRModal.style.display = "block";
    });
  }

  if (howToUseBtn) {
    howToUseBtn.addEventListener("click", function (e) {
      e.preventDefault();
      howToUseModal.style.display = "block";
    });
  }

  if (discoverFeaturesBtn) {
    discoverFeaturesBtn.addEventListener("click", function (e) {
      e.preventDefault();
      featuresModal.style.display = "block";
    });
  }

  modalCloses.forEach(function (closeBtn) {
    closeBtn.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal");
      document.getElementById(modalId).style.display = "none";
    });
  });

  window.addEventListener("click", function (e) {
    if (e.target.classList.contains("modal")) {
      e.target.style.display = "none";
    }
  });
}

// Navigation function
function goToStep(step) {
  // Hide all steps
  for (let i = 1; i <= 4; i++) {
    const stepElement = document.getElementById(`step${i}`);
    if (stepElement) {
      stepElement.style.display = "none";
    }
  }

  // Show selected step
  const selectedStep = document.getElementById(`step${step}`);
  if (selectedStep) {
    selectedStep.style.display = "block";
  }

  // Update form tabs
  const formTabs = document.querySelectorAll(".form-tab");
  formTabs.forEach((tab, index) => {
    if (index + 1 === step) {
      tab.classList.add("form-tab-active");
    } else {
      tab.classList.remove("form-tab-active");
    }
  });

  currentStep = step;
  console.log("Moved to step:", step);
}

// URL Validation
function isValidURL(string) {
  try {
    const url = new URL(string);
    return url.protocol === "http:" || url.protocol === "https:";
  } catch (_) {
    return false;
  }
}

// Generate QR Code
function generateQRCode() {
  console.log("Generating QR Code with data:", qrCodeData);

  const formData = new FormData();
  formData.append("data", qrCodeData.content);
  formData.append("type", qrCodeData.type);
  formData.append("updateLater", qrCodeData.updateLater);
  formData.append("dynamicTracking", qrCodeData.dynamicTracking);
  formData.append("centerText", qrCodeData.centerText);
  formData.append("centerTextColor", qrCodeData.centerTextColor);

  if (qrCodeData.logo) {
    formData.append("logo", qrCodeData.logo);
  }

  // Call PHP backend to generate QR code
  fetch("generate-qr.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        console.log("QR Code generated:", result.qrCodeUrl);
        // Store the QR code ID for later use in download
        qrCodeData.id = result.qrCodeId;
        qrCodeData.imageUrl = result.qrCodeUrl;
        displayQRCode(result.qrCodeUrl);
      } else {
        console.error("Error generating QR code:", result.error);
        alert("Failed to generate QR code. Please try again.");
      }
    })
    .catch((error) => {
      console.error("Network error:", error);
      alert("Network error. Please check your connection.");
    });
}

// Display QR Code in preview
function displayQRCode(qrCodeUrl) {
  const qrCodeElement = document.getElementById("qrCode");
  if (qrCodeElement) {
    qrCodeElement.innerHTML = `<img src="${qrCodeUrl}" alt="Generated QR Code" style="width: 100%; height: 100%; object-fit: contain;">`;
  }
}

// Download QR Code
function downloadQRCode(format, useCustomSettings = false) {
  let settings = {
    format: format,
    size: 400,
    dpi: 72,
  };

  if (useCustomSettings) {
    const sizeInput = document.getElementById("qrSize");
    const dpiInput = document.getElementById("qrDPI");

    settings.size = sizeInput ? parseInt(sizeInput.value) : 400;
    settings.dpi = dpiInput ? parseInt(dpiInput.value) : 72;
  }

  console.log("Downloading QR Code:", format, settings);

  // Call PHP backend to download QR code
  const params = new URLSearchParams({
    format: settings.format,
    size: settings.size,
    dpi: settings.dpi,
    qrCodeId: qrCodeData.id || "",
  });

  window.location.href = `download-qr.php?${params.toString()}`;
}

// Save QR Code to Database
function saveQRCode() {
  fetch("save-qr.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(qrCodeData),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        console.log("QR Code saved with ID:", result.id);
        qrCodeData.id = result.id;
      } else {
        console.error("Error saving QR code:", result.error);
      }
    })
    .catch((error) => {
      console.error("Network error:", error);
    });
}

// Load User's QR Codes
function loadUserQRCodes(userId) {
  fetch(`get-qr-codes.php?user_id=${userId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log("Loaded QR codes:", data.qrCodes);
      } else {
        console.error("Error loading QR codes:", data.error);
      }
    })
    .catch((error) => {
      console.error("Network error:", error);
    });
}

// Delete QR Code or Barcode
function deleteCode(codeId) {
  if (
    !confirm(
      "Are you sure you want to delete this code? This action cannot be undone.",
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("code_id", codeId);

  fetch("delete-code.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        console.log("Code deleted successfully");
        // Reload the page to reflect changes
        location.reload();
      } else {
        alert("Error deleting code: " + (result.error || "Unknown error"));
        console.error("Error deleting code:", result.error);
      }
    })
    .catch((error) => {
      alert("Network error: " + error);
      console.error("Network error:", error);
    });
}

// Initialize delete button event listeners
document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".btn-delete-code");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const codeId = this.getAttribute("data-code-id");
      deleteCode(codeId);
    });
  });
});
