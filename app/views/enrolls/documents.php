<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>American Assist | Document Upload</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo URLROOT; ?>/public/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo URLROOT; ?>/public/img/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: "#003366",
                            red: "#D12027",
                            light: "#F4F4F4"
                        }
                    },
                    fontFamily: {
                        sans: ["Roboto", "ui-sans-serif", "system-ui", "sans-serif"]
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="<?php echo URLROOT; ?>" class="flex items-center gap-3" aria-label="American Assist Home">
                <img src="<?php echo URLROOT; ?>/public/img/AALogo.png" alt="American Assist" class="h-10 w-auto sm:h-11" />
            </a>

            <nav class="hidden md:flex md:items-center md:justify-center md:gap-8" aria-label="Primary navigation">
                <a href="<?php echo URLROOT; ?>" class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Home</a>
                <a href="<?php echo URLROOT; ?>/#about" class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">About Us</a>
                <a href="<?php echo URLROOT; ?>/#plans" class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Plans</a>
                <a href="<?php echo URLROOT; ?>/#contact" class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Contact</a>
            </nav>

            <a href="<?php echo URLROOT; ?>" class="hidden rounded-md bg-brand-red px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 md:inline-flex">
                Apply Now
            </a>
        </div>
    </header>

    <main class="py-10 sm:py-14">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <?php if ($data['customer_id']) { ?>
                <section id="thankyou" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-6 sm:p-8">
                    <h3 class="text-2xl font-bold text-emerald-800">Thank You</h3>
                    <p class="mt-2 text-sm text-emerald-700 sm:text-base">
                        Thank you! We have received your documents and will now continue with the enrollment process.
                    </p>
                </section>

                <section id="uploadSection" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <p class="inline-flex rounded-full bg-brand-light px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-navy">Enrollment Verification</p>
                    <h1 class="mt-3 text-2xl font-bold text-brand-navy sm:text-3xl">Hello <?php echo $data['first_name'] . ' ' . $data['last_name']; ?>,</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
                        We received your application for our Lifeline program. To proceed with your enrollment, we need to verify your eligibility by collecting important documents.
                    </p>
                    <p class="mt-2 text-sm leading-6 text-slate-600 sm:text-base">
                        Please upload the required files below. Make sure the images are clear and all information is visible. Accepted formats: JPG, PNG, PDF.
                    </p>

                    <form id="uploadForm" class="mt-6 space-y-6">
                        <div>
                            <label for="identityProof" class="mb-2 block text-sm font-semibold text-slate-800">Proof of Identity</label>
                            <input class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 p-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-brand-navy file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#02284d] focus:outline-none focus:ring-2 focus:ring-brand-navy/30" type="file" id="identityProof" accept=".jpg,.jpeg,.png,.pdf" capture="camera" required />
                            <p class="mt-2 text-xs text-slate-500">Example: ID card, driver license, or passport.</p>
                            <div id="identityPreview" class="mt-3"></div>
                        </div>

                        <div>
                            <label for="benefitProof" class="mb-2 block text-sm font-semibold text-slate-800">Proof of Benefit</label>
                            <input class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 p-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-brand-navy file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#02284d] focus:outline-none focus:ring-2 focus:ring-brand-navy/30" type="file" id="benefitProof" accept=".jpg,.jpeg,.png,.pdf" capture="camera" required />
                            <p class="mt-2 text-xs text-slate-500">Example: eligibility letter or benefit notice.</p>
                            <div id="benefitPreview" class="mt-3"></div>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button id="submitButton" type="submit" class="inline-flex items-center justify-center rounded-md bg-brand-red px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2">
                                Submit Documents
                            </button>
                            <a href="<?php echo URLROOT; ?>/pages/thankyou/<?php echo urlencode($data['customer_id']); ?>" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                Upload Later
                            </a>
                        </div>

                        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $data['customer_id']; ?>" />
                        <div id="response" class="text-sm font-medium text-red-600" role="status" aria-live="polite"></div>
                    </form>
                </section>
            <?php } else { ?>
                <section class="rounded-2xl border border-red-200 bg-red-50 p-6 sm:p-8">
                    <h3 class="text-2xl font-bold text-red-800">Error</h3>
                    <p class="mt-2 text-sm text-red-700 sm:text-base">
                        Invalid access. <?php echo $data['msg']; ?> Please start your enrollment process again.
                        <a href="<?php echo URLROOT; ?>" class="font-semibold underline decoration-red-700 underline-offset-2">Click here to begin.</a>
                    </p>
                </section>
            <?php } ?>
        </div>
    </main>

    <footer id="contact" class="bg-brand-navy text-slate-100">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div>
                <h3 class="text-lg font-bold text-white">American Assistance</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">Reliable telecommunications access for qualified households through federally supported social programs.</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-white">Site Links</h4>
                <ul class="mt-3 space-y-2 text-sm text-slate-300">
                    <li><a href="<?php echo URLROOT; ?>" class="hover:text-white">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/#about" class="hover:text-white">About Us</a></li>
                    <li><a href="<?php echo URLROOT; ?>/#plans" class="hover:text-white">Plans</a></li>
                    <li><a href="<?php echo URLROOT; ?>" class="hover:text-white">Apply Now</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-white">Contact</h4>
                <ul class="mt-3 space-y-2 text-sm text-slate-300">
                    <li>Phone: (800) 555-0142</li>
                    <li>Email: support@americanassistance.net</li>
                    <li>Hours: Mon-Fri, 9:00 AM-6:00 PM</li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-white">Follow Us</h4>
                <div class="mt-3 flex items-center gap-3">
                    <span class="rounded-md bg-white/10 p-2 text-xs">FB</span>
                    <span class="rounded-md bg-white/10 p-2 text-xs">IG</span>
                    <span class="rounded-md bg-white/10 p-2 text-xs">X</span>
                </div>
            </div>
        </div>

        <div class="border-t border-white/15">
            <div class="mx-auto max-w-7xl px-4 py-6 text-xs leading-6 text-slate-300 sm:px-6 lg:px-8">
                <p>Lifeline is a government assistance program. Eligibility is determined by federal or state criteria. Service is non-transferable, and only one discount is available per household. Terms and conditions apply.</p>
                <p class="mt-2">&copy; 2026 American Assistance. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        let identityBase64 = "";
        let benefitBase64 = "";

        function createRemoveButton(input, preview, clearBase64Callback) {
            const removeBtn = document.createElement("button");
            removeBtn.textContent = "Remove";
            removeBtn.className = "mt-3 inline-flex rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700";
            removeBtn.type = "button";
            removeBtn.onclick = function () {
                input.value = "";
                preview.innerHTML = "";
                clearBase64Callback();
            };
            return removeBtn;
        }

        function previewAndConvert(inputId, previewId, setBase64Callback, clearBase64Callback) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (!input || !preview) {
                return;
            }

            input.addEventListener("change", function () {
                const file = input.files[0];
                if (!file) {
                    return;
                }

                preview.innerHTML = "";

                if (file.type.startsWith("image/")) {
                    const img = new Image();
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        img.src = e.target.result;
                    };

                    img.onload = function () {
                        const canvas = document.createElement("canvas");
                        const MAX_WIDTH = 800;
                        const scaleSize = MAX_WIDTH / img.width;

                        canvas.width = MAX_WIDTH;
                        canvas.height = img.height * scaleSize;

                        const ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                        const compressedBase64 = canvas.toDataURL("image/jpeg", 0.7);
                        setBase64Callback(compressedBase64);

                        const imagePreview = document.createElement("img");
                        imagePreview.className = "max-h-44 rounded-lg border border-slate-300 p-2";
                        imagePreview.src = compressedBase64;
                        imagePreview.alt = "Selected file preview";

                        preview.appendChild(imagePreview);
                        preview.appendChild(createRemoveButton(input, preview, clearBase64Callback));
                    };

                    reader.readAsDataURL(file);
                    return;
                }

                if (file.type === "application/pdf") {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        setBase64Callback(e.target.result);

                        const textPreview = document.createElement("p");
                        textPreview.className = "rounded-md border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-700";
                        textPreview.textContent = "PDF selected: " + file.name;

                        preview.appendChild(textPreview);
                        preview.appendChild(createRemoveButton(input, preview, clearBase64Callback));
                    };
                    reader.readAsDataURL(file);
                    return;
                }

                const unsupported = document.createElement("p");
                unsupported.className = "text-sm text-red-600";
                unsupported.textContent = "Unsupported file type.";
                preview.appendChild(unsupported);
            });
        }

        previewAndConvert(
            "identityProof",
            "identityPreview",
            function (b64) { identityBase64 = b64; },
            function () { identityBase64 = ""; }
        );

        previewAndConvert(
            "benefitProof",
            "benefitPreview",
            function (b64) { benefitBase64 = b64; },
            function () { benefitBase64 = ""; }
        );

        const uploadForm = document.getElementById("uploadForm");
        if (uploadForm) {
            uploadForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const responseBox = document.getElementById("response");
                const submitButton = document.getElementById("submitButton");

                if (!identityBase64 || !benefitBase64) {
                    if (responseBox) {
                        responseBox.textContent = "Please upload both required documents.";
                    }
                    return;
                }

                if (responseBox) {
                    responseBox.textContent = "";
                }
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = "Submitting...";
                }

                const payload = {
                    identity_proof: identityBase64,
                    benefit_proof: benefitBase64,
                    customer_id: document.getElementById("customer_id").value
                };

                fetch("<?php echo URLROOT; ?>/enrolls/saveDocuments", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                })
                    .then(function (res) { return res.json(); })
                    .then(function (response) {
                        if (response && response.success && response.redirect_url) {
                            window.location.assign(response.redirect_url);
                            return;
                        }

                        document.getElementById("uploadSection").classList.add("hidden");
                        document.getElementById("thankyou").classList.remove("hidden");
                    })
                    .catch(function () {
                        if (responseBox) {
                            responseBox.textContent = "Upload failed. Please try again.";
                        }
                    })
                    .finally(function () {
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.textContent = "Submit Documents";
                        }
                    });
            });
        }
    </script>
</body>
</html>