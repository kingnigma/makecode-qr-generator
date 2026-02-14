// Global state management
let currentStep = 1;
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
});

// Initialize all event listeners
function initializeEventListeners() {
  // Tab switching (QR Code / Bar Code)
  const tabs = document.querySelectorAll(".tab");
  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("tab-active"));
      this.classList.add("tab-active");
      const tabType = this.getAttribute("data-tab");
      toggleMainTab(tabType);
      console.log("Switched to:", tabType);
    });
  });

  // Toggle QR <-> Barcode UI and preview
  function toggleMainTab(tabType) {
    const barcodeModeEls = document.querySelectorAll(".barcode-mode");
    const qrPreview = document.getElementById("qrCode");
    const barcodePreview = document.getElementById("barcodePreview");
    const dataTypeGrid = document.querySelector(".data-type-grid");

    if (tabType === "bar") {
      // Switch to BARCODE mode
      goToStep(1);

      // Show barcode-specific UI in step 1
      barcodeModeEls.forEach((el) => (el.style.display = "block"));

      // Hide QR code data type grid
      if (dataTypeGrid) dataTypeGrid.style.display = "none";

      // Switch preview panels
      if (qrPreview) qrPreview.style.display = "none";
      if (barcodePreview) barcodePreview.style.display = "block";
    } else {
      // Switch to QR CODE mode
      goToStep(1);

      // Hide barcode-specific UI
      barcodeModeEls.forEach((el) => (el.style.display = "none"));

      // Show QR code data type grid
      if (dataTypeGrid) dataTypeGrid.style.display = "grid";

      // Switch preview panels
      if (qrPreview) qrPreview.style.display = "block";
      if (barcodePreview) barcodePreview.style.display = "none";
    }
  }

  // Data Type Selection
  const dataTypeButtons = document.querySelectorAll(".data-type-btn");
  dataTypeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      dataTypeButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      selectedDataType = this.getAttribute("data-type");
      qrCodeData.type = selectedDataType;
      console.log("Selected data type:", selectedDataType);

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
      const urlInput = document.getElementById("urlInput");
      const url = urlInput.value.trim();

      if (!url) {
        alert("Please enter a URL");
        return;
      }

      if (!isValidURL(url)) {
        alert("Please enter a valid URL (e.g., https://example.com)");
        return;
      }

      qrCodeData.content = url;
      goToStep(3);
    });
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

  // Back buttons
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
            .map((s) => s.trim())
            .filter(Boolean);
          previewList = lines.slice(0, 50);
          document.getElementById("barcodeDataPreview").innerText =
            previewList.join("\n");
        };
        reader.readAsText(file);
        // show preview after read
        document.getElementById("barcodeDataPreview").innerText =
          "Loading file preview...";
        // move to next; actual preview will be filled in reader.onload
        goToStep(2);
        return;
      }

      document.getElementById("barcodeDataPreview").innerText =
        previewList.join("\n");
      goToStep(2);
    });
  }

  const barcodePreviewSamplesBtn = document.getElementById(
    "barcodePreviewSamplesBtn",
  );
  if (barcodePreviewSamplesBtn) {
    barcodePreviewSamplesBtn.addEventListener("click", function () {
      // build a quick sample and render using JsBarcode (client-side)
      const sample =
        document.querySelector('input[name="barcodeSourceMode"]:checked')
          .value === "counter"
          ? (document.getElementById("barcodePrefix").value || "") +
            (document.getElementById("barcodeStart").value || "1001")
          : "SAMPLE123";
      const sym = document.getElementById("barcodeSymbology").value;

      const canvasContainer = document.getElementById("barcodePreviewCanvas");
      canvasContainer.innerHTML = "";
      const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg.setAttribute("id", "jsBarcodePreview");
      canvasContainer.appendChild(svg);

      // JsBarcode supports code128, code39, ean13, upc
      try {
        JsBarcode(svg, sample, {
          format: sym === "upc-a" ? "upc" : sym.replace("-", ""),
          displayValue: true,
          fontSize: 14,
        });
      } catch (err) {
        canvasContainer.innerText =
          "Preview not available for selected symbology.";
      }
    });
  }

  const styleYourBarcodeBtn = document.getElementById("styleYourBarcodeBtn");
  if (styleYourBarcodeBtn) {
    styleYourBarcodeBtn.addEventListener("click", function () {
      // go to style step and show barcode style panel
      goToStep(3);
      document.getElementById("barcodeStyle").style.display = "block";
      // hide QR style parts if needed (they are separate blocks so no extra work)
    });
  }

  const backToBarcodeDestination = document.getElementById(
    "backToBarcodeDestination",
  );
  if (backToBarcodeDestination) {
    backToBarcodeDestination.addEventListener("click", function () {
      goToStep(2);
    });
  }

  const barcodeBackToDataType = document.getElementById(
    "barcodeBackToDataType",
  );
  if (barcodeBackToDataType) {
    barcodeBackToDataType.addEventListener("click", function () {
      goToStep(1);
    });
  }

  // Finish generate (barcode)
  const finishGenerateBarBtn = document.getElementById("finishGenerateBarBtn");
  let lastBarcodeResult = null;
  if (finishGenerateBarBtn) {
    finishGenerateBarBtn.addEventListener("click", function () {
      const mode = document.querySelector(
        'input[name="barcodeSourceMode"]:checked',
      ).value;
      const sym = document.getElementById("barcodeSymbology").value;
      const projectName =
        document.getElementById("barcodeProjectName").value || "";
      const width = parseInt(
        document.getElementById("barcodeWidth").value || "600",
        10,
      );
      const height = parseInt(
        document.getElementById("barcodeHeight").value || "120",
        10,
      );
      const fg = document.getElementById("barcodeFgColor").value || "#000000";
      const bg = document.getElementById("barcodeBgColor").value || "#ffffff";
      const fontSize = parseInt(
        document.getElementById("barcodeFontSize").value || "12",
        10,
      );
      const preventDup = document.getElementById("barcodePreventDup").checked;

      const formData = new FormData();
      formData.append("mode", mode);
      formData.append("projectName", projectName);
      formData.append("symbology", sym);
      formData.append("width", width);
      formData.append("height", height);
      formData.append("fgColor", fg);
      formData.append("bgColor", bg);
      formData.append("fontSize", fontSize);
      formData.append("preventDuplicates", preventDup ? "1" : "0");

      if (mode === "counter") {
        formData.append(
          "prefix",
          document.getElementById("barcodePrefix").value || "",
        );
        formData.append(
          "start",
          document.getElementById("barcodeStart").value || "1",
        );
        formData.append(
          "increment",
          document.getElementById("barcodeIncrement").value || "1",
        );
        formData.append(
          "count",
          document.getElementById("barcodeCount").value || "1",
        );
      } else {
        const fileInput = document.getElementById("barcodeDataFile");
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
          alert("Please upload a data file");
          return;
        }
        formData.append("dataFile", fileInput.files[0]);
      }

      fetch("generate-barcode.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((resp) => {
          if (!resp.success) {
            alert(
              "Failed to generate barcode(s): " + (resp.error || "unknown"),
            );
            return;
          }
          lastBarcodeResult = resp;
          // populate finish preview and download links
          const previewEl = document.getElementById("barcodeFinishPreview");
          previewEl.innerHTML = "";
          if (resp.generated && resp.generated.length) {
            resp.generated.forEach((g) => {
              const img = document.createElement("img");
              img.src = g.url;
              img.style.maxWidth = "100%";
              img.style.margin = "8px 0";
              previewEl.appendChild(img);
            });
          }
          if (resp.zipUrl) {
            const zipLink = document.getElementById("downloadBarcodeZipLink");
            zipLink.href = resp.zipUrl;
            zipLink.style.display = "inline-block";
          }

          goToStep(4);
          // show barcode finish area
          document.getElementById("barcodeFinish").style.display = "block";
          // hide standard QR download
          document.getElementById("standardDownload").style.display = "none";
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
      if (
        !lastBarcodeResult ||
        !lastBarcodeResult.generated ||
        !lastBarcodeResult.generated.length
      ) {
        alert("No generated barcode available to download");
        return;
      }
      // if multiple generated, open first; ZIP is available via link
      const url = lastBarcodeResult.generated[0].url;
      const a = document.createElement("a");
      a.href = url;
      a.download = url.split("/").pop();
      document.body.appendChild(a);
      a.click();
      a.remove();
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
  const startCreatingBtn = document.querySelector(".hero-buttons .btn-primary");
  if (startCreatingBtn) {
    startCreatingBtn.addEventListener("click", function () {
      const generatorSection = document.querySelector(".generator-section");
      if (generatorSection) {
        generatorSection.scrollIntoView({ behavior: "smooth" });
      }
    });
  }
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
