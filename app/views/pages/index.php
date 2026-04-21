<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo SITENAME; ?></title>
<meta name="description" content="Get a free government phone and service through the Lifeline Assistance Program. Check if you qualify today — unlimited talk, text, and data at no cost." />

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
theme: {
extend: {
colors: {
brand: {
navy: "#003366",
red:  "#D12027",
light: "#F4F4F4"
}
},
fontFamily: {
sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"]
}
}
}
};
</script>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo URLROOT; ?>/public/img/favicon.png" />

<style>
label.error {
display: block;
margin-top: 4px;
font-size: 0.75rem;
font-weight: 500;
color: #dc2626;
}
input.error,
input.error:focus {
border-color: #ef4444 !important;
box-shadow: 0 0 0 3px rgba(239,68,68,.15) !important;
}
</style>
</head>
<body class="bg-white text-slate-800 antialiased">

<!-- SKIP LINK -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-3 focus:top-3 focus:z-50 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-brand-navy focus:shadow">
Skip to main content
</a>

<!-- NAVBAR -->
<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
<div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

<a href="#" class="flex items-center gap-2" aria-label="Galaxy Free Wireless Home">
<!-- <img src="<?php echo URLROOT; ?>/public/img/AALogo.png" alt="Galaxy Free Wireless" class="h-10 w-auto sm:h-11" /> -->
 <h2 class="text-center text-xl font-black text-brand-navy sm:text-xl">
		Galaxy Free Wireless
	</h2>
</a>

<nav class="hidden md:flex md:items-center md:gap-8" aria-label="Primary navigation">
<!-- <a href="#"              class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Home</a>
<a href="#how-it-works"  class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">How It Works</a>
<a href="#benefits"      class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Benefits</a>
<a href="#qualifies"     class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Who Qualifies</a>
<a href="#contact"       class="text-sm font-medium text-slate-700 transition hover:text-brand-navy">Contact</a> -->
</nav>

<div class="hidden md:block">
<a href="#check-availability"
   class="inline-flex items-center rounded-md bg-brand-red px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2">
Check Availability
</a>
</div>

<button
id="menu-toggle"
class="inline-flex items-center justify-center rounded-md border border-slate-300 p-2 text-brand-navy transition hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand-navy md:hidden"
aria-controls="mobile-menu"
aria-expanded="false"
aria-label="Toggle menu"
>
<svg id="icon-open"  class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
</svg>
<svg id="icon-close" class="hidden h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
</svg>
</button>
</div>

<div id="mobile-menu" class="hidden border-t border-slate-200 bg-white px-4 py-4 md:hidden">
<nav class="space-y-2" aria-label="Mobile navigation">
<a href="#"              class="block rounded-md px-3 py-2 font-medium text-slate-700 hover:bg-brand-light hover:text-brand-navy">Home</a>
<a href="#how-it-works"  class="block rounded-md px-3 py-2 font-medium text-slate-700 hover:bg-brand-light hover:text-brand-navy">How It Works</a>
<a href="#benefits"      class="block rounded-md px-3 py-2 font-medium text-slate-700 hover:bg-brand-light hover:text-brand-navy">Benefits</a>
<a href="#qualifies"     class="block rounded-md px-3 py-2 font-medium text-slate-700 hover:bg-brand-light hover:text-brand-navy">Who Qualifies</a>
<a href="#contact"       class="block rounded-md px-3 py-2 font-medium text-slate-700 hover:bg-brand-light hover:text-brand-navy">Contact</a>
<a href="#check-availability" class="mt-2 block rounded-md bg-brand-red px-3 py-2 text-center font-semibold text-white hover:bg-red-700">
Check Availability
</a>
</nav>
</div>
</header>

<!-- MAIN CONTENT -->
<main id="main-content">

<!-- HERO BANNER -->
<section class="relative isolate overflow-hidden">
<img
src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1920&q=80"
alt="Family staying connected with free wireless service"
class="h-[50vh] min-h-[560px] w-full object-cover object-center"
/>
<div class="absolute inset-0 bg-gradient-to-r from-brand-navy/90 via-brand-navy/70 to-brand-navy/30"></div>
<div class="absolute inset-0 flex items-center">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="max-w-3xl text-white">
<!-- <span class="inline-flex rounded-full bg-brand-red/90 px-3 py-1 text-xs font-semibold uppercase tracking-widest">
Federal Lifeline Program
</span> -->
<h1 class="mt-4 text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">
FREE WIRELESS SERVICE<br />for Those Who&nbsp;Qualify
</h1>
<p class="mt-5 max-w-xl text-base leading-7 text-slate-200 sm:text-lg">
Get FREE Wireless Service through the Lifeline Benefit Program. With eligibility you receive free talk, text and high-speed data every month – at no cost to you!
</p>
<!-- <div class="mt-8 flex flex-wrap gap-4">
<a href="#check-availability"
   class="inline-flex items-center rounded-md bg-brand-red px-7 py-3.5 text-base font-semibold text-white shadow-lg transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2 focus:ring-offset-brand-navy">
Check Availability
</a>
<a href="#how-it-works"
   class="inline-flex items-center rounded-md border border-white/60 bg-white/10 px-7 py-3.5 text-base font-semibold text-white backdrop-blur transition hover:bg-white/20">
Learn More
</a>
</div> -->
</div>
</div>
</div>
</section>

<!-- STATS STRIP -->
<!-- <section class="bg-brand-navy py-10" aria-label="Program statistics">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<dl class="grid grid-cols-2 gap-6 text-center sm:grid-cols-4">
<div>
<dt class="text-3xl font-black text-white">1M+</dt>
<dd class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-300">Households Served</dd>
</div>
<div>
<dt class="text-3xl font-black text-white">50</dt>
<dd class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-300">States Covered</dd>
</div>
<div>
<dt class="text-3xl font-black text-white">$0</dt>
<dd class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-300">Monthly Cost</dd>
</div>
<div>
<dt class="text-3xl font-black text-white">100%</dt>
<dd class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-300">Free Service</dd>
</div>
</dl>
</div>
</section> -->

<!-- AVAILABILITY CHECK FORM -->
<section id="check-availability" class="relative z-10 -mt-24">
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
<div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-lg sm:p-10">

	<!-- <div class="mb-3 flex justify-center">
		<span class="inline-flex items-center gap-2 rounded-full bg-brand-navy/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-brand-navy">
			<svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
				<path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
			</svg>
			Eligibility Check
		</span>
	</div> -->

	<h2 class="text-center text-2xl font-black text-brand-navy sm:text-3xl">
		Get Your Benefit Now!
	</h2>
	<p class="mt-2 text-center text-sm text-slate-500">
		Enter your ZIP code and email address to instantly see if the Lifeline program is available where you live.
	</p>

	<form id="availability-form" action="<?php echo URLROOT; ?>/pages/saveLead" method="post" novalidate class="mt-8">

		<!-- Inline row: ZIP — Email — Button -->
		<div class="flex flex-col gap-3 sm:flex-row sm:items-start">

			<!-- ZIP Code -->
			<div class="flex-1 zip-wrap">
				<label for="zipcode" class="mb-1.5 block text-sm font-semibold text-slate-700">
					ZIP Code <span class="text-brand-red">*</span>
				</label>
				<div class="relative">
					<span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
						<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
						</svg>
					</span>
					<input
						id="zipcode"
						name="zipcode"
						type="text"
						inputmode="numeric"
						maxlength="5"
						placeholder="ZIP Code"
						class="w-full rounded-lg border border-slate-300 py-3.5 pl-11 pr-4 text-slate-900 placeholder-slate-400 transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/30"
					/>
				</div>
			</div>

			<!-- Email -->
			<div class="flex-[2] email-wrap">
				<label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">
					Email Address <span class="text-brand-red">*</span>
				</label>
				<div class="relative">
					<span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
						<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
						</svg>
					</span>
					<input
						id="email"
						name="email"
						type="email"
						placeholder="Email Address"
						class="w-full rounded-lg border border-slate-300 py-3.5 pl-11 pr-4 text-slate-900 placeholder-slate-400 transition focus:border-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-navy/30"
					/>
				</div>
			</div>

			<!-- Submit -->
			<div class="flex flex-col justify-end sm:pt-[1.875rem]">
				<button
					type="submit"
					class="w-full whitespace-nowrap rounded-lg bg-brand-red px-7 py-3.5 text-base font-bold text-white shadow-md transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2 sm:w-auto"
				>
					Get Started! &rarr;
				</button>
			</div>

		</div><!-- /inline row -->

		<!-- Hidden fields populated by ZIP lookup -->
		<input type="hidden" id="state" name="state" value="" />
		<input type="hidden" id="city"  name="city"  value="" />

		<!-- ZIP lookup feedback -->
		<p id="zip-location" class="hidden mt-3 text-center text-sm text-brand-navy font-medium"></p>

		<p class="mt-4 text-center text-xs text-slate-400">
			We respect your privacy. Your information is safe and will never be sold.
		</p>
		<div id="form-status" class="hidden mt-3 rounded-lg border px-4 py-3 text-sm font-medium text-center" role="status" aria-live="polite"></div>
	</form>
</div>
</div>
</section>

<!-- HOW IT WORKS -->
<section id="how-it-works" class="py-16 sm:py-20">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="mx-auto max-w-2xl text-center">
<p class="text-sm font-semibold uppercase tracking-widest text-brand-red">Simple &amp; Fast</p>
<h2 class="mt-2 text-3xl font-black text-brand-navy sm:text-4xl">How It Works</h2>
<p class="mt-3 text-slate-500">Getting your free wireless service takes just three easy steps.</p>
</div>

<div class="mt-12 grid gap-8 sm:grid-cols-3">
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-navy text-2xl font-black text-white shadow-lg">1</div>
<img
src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80"
alt="Person checking eligibility online"
class="h-48 w-full rounded-xl object-cover shadow-sm"
/>
<h3 class="mt-4 text-lg font-bold text-brand-navy">Check Your Eligibility</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Enter your zip code and email to confirm service area</p>
</div>
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-navy text-2xl font-black text-white shadow-lg">2</div>
<img
src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=80"
alt="Completing an online enrollment form"
class="h-48 w-full rounded-xl object-cover shadow-sm"
/>
<h3 class="mt-4 text-lg font-bold text-brand-navy">Complete Enrollment</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Fill out a short application, provide qualifying program or income documentation, and submit — all online.</p>
</div>
<div class="text-center">
<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-navy text-2xl font-black text-white shadow-lg">3</div>
<img
src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=600&q=80"
alt="Receiving a free smartphone"
class="h-48 w-full rounded-xl object-cover shadow-sm"
/>
<h3 class="mt-4 text-lg font-bold text-brand-navy">Get Connected</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Once approved we ship your free SIM card or device so you can enjoy your free wireless service.</p>
</div>
</div>
</div>
</section>

<!-- BENEFITS -->
<section id="benefits" class="bg-brand-light py-16 sm:py-20">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="mx-auto max-w-2xl text-center">
<p class="text-sm font-semibold uppercase tracking-widest text-brand-red">What You Get</p>
<h2 class="mt-2 text-3xl font-black text-brand-navy sm:text-4xl">Your Lifeline Benefits</h2>
<p class="mt-3 text-slate-500">Everything you need to stay connected — free every month for eligible households.</p>
</div>

<div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">Unlimited Talk &amp; Text</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Stay in touch with family, friends, healthcare providers, and employers with no limits on domestic calls or texts.</p>
</article>

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">High-Speed Data</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Browse the web, access telehealth services, manage job applications, and stream content with reliable 4G LTE coverage.</p>
</article>

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<rect x="7" y="2" width="10" height="20" rx="2" ry="2"></rect>
<path stroke-linecap="round" stroke-linejoin="round" d="M11 18h2" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">Free SIM Card &amp; Device</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Approved applicants receive a no-cost SIM card — and select plans include a free smartphone shipped directly to your door.</p>
</article>

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">Federally Supported</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Lifeline is a federal program regulated by the FCC, ensuring your benefit is secure, reliable, and backed by the government.</p>
</article>

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">Absolutely \$0 Cost</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">No hidden fees, no credit checks, and no monthly bills. Qualified households receive full wireless service for free.</p>
</article>

<article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
<div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-brand-navy/10 text-brand-navy">
<svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
</div>
<h3 class="text-lg font-bold text-brand-navy">Community-Focused</h3>
<p class="mt-2 text-sm leading-6 text-slate-500">Dedicated enrollment support from real people who understand your community and are committed to helping you get connected.</p>
</article>

</div>
</div>
</section>

<!-- WHO QUALIFIES -->
<section id="qualifies" class="py-16 sm:py-20">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<div class="grid items-center gap-12 lg:grid-cols-2">

<div>
<p class="text-sm font-semibold uppercase tracking-widest text-brand-red">Eligibility</p>
<h2 class="mt-2 text-3xl font-black text-brand-navy sm:text-4xl">Who Qualifies for Lifeline?</h2>
<p class="mt-4 leading-7 text-slate-500">
You may be eligible if your household income is at or below 135% of the federal poverty guidelines, or if you participate in any of these federal assistance programs:
</p>

<ul class="mt-6 grid gap-3 sm:grid-cols-2">
<?php
$programs = [
"SNAP (Food Stamps)",
"Medicaid",
"Supplemental Security Income (SSI)",
"Federal Public Housing / Section 8",
"Veterans Pension or Survivors Benefit",
"Bureau of Indian Affairs General Assistance",
"Tribal TANF (TTANF)",
"Food Distribution on Indian Reservations (FDPIR)",
"Head Start (income-eligible)",
"Household Income at or below 135% FPG",
];
foreach ($programs as $p): ?>
<li class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700">
<svg class="h-4 w-4 shrink-0 text-brand-red" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
</svg>
<?php echo htmlspecialchars($p); ?>
</li>
<?php endforeach; ?>
</ul>

<a href="#check-availability"
   class="mt-8 inline-flex items-center rounded-lg bg-brand-navy px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#02284d] focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2">
See If I Qualify &rarr;
</a>
</div>

<div class="grid grid-cols-2 gap-4">
<img
src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80"
alt="Family benefiting from Lifeline program"
class="col-span-2 h-52 w-full rounded-2xl object-cover shadow-sm"
/>
<img
src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=600&q=80"
alt="Senior citizen using smartphone"
class="h-44 w-full rounded-2xl object-cover shadow-sm"
/>
<img
src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80"
alt="Young person staying connected"
class="h-44 w-full rounded-2xl object-cover shadow-sm"
/>
</div>
</div>
</div>
</section>

<!-- COMMUNITY PHOTO BAND -->
<!-- <section class="bg-brand-navy py-14" aria-label="Community photos">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
<p class="mb-8 text-center text-sm font-semibold uppercase tracking-widest text-slate-300">Connecting Communities Nationwide</p>
<div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
<img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80"
     alt="Community members connected" class="h-44 w-full rounded-xl object-cover opacity-90" />
<img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=600&q=80"
     alt="Volunteers helping enroll applicants" class="h-44 w-full rounded-xl object-cover opacity-90" />
<img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=600&q=80"
     alt="Families using mobile service" class="h-44 w-full rounded-xl object-cover opacity-90" />
<img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80"
     alt="People using digital services" class="h-44 w-full rounded-xl object-cover opacity-90" />
</div>
</div>
</section> -->

<!-- FINAL CTA BANNER -->
<section class="bg-brand-light py-16 sm:py-20">
<div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
<h2 class="text-3xl font-black text-brand-navy sm:text-4xl">Ready to Get Your Free Phone Service?</h2>
<p class="mx-auto mt-4 max-w-xl text-slate-500">
Thousands of qualifying households are already connected. Check availability in your area and start your free enrollment today.
</p>
<a href="#check-availability"
   class="mt-8 inline-flex items-center rounded-lg bg-brand-red px-8 py-4 text-base font-bold text-white shadow-lg transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2">
Check Availability Now &rarr;
</a>
</div>
</section>

</main>

<!-- FOOTER -->
<?php include APPROOT . '/views/inc/footer.php'; ?>

<!-- COOKIE BANNER -->
<!-- <div id="cookie-banner" class="fixed inset-x-0 bottom-4 z-[60] hidden px-4 sm:px-6 lg:px-8">
<div class="mx-auto max-w-5xl rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-2xl ring-1 ring-slate-200 backdrop-blur sm:p-5">
<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
<p class="text-sm leading-6 text-slate-600">
We use cookies to improve your experience and support enrollment.
See our <a href="<?php echo URLROOT; ?>/pages/privacy" class="font-semibold text-brand-navy underline underline-offset-4 hover:text-brand-red">Privacy Policy</a>
and <a href="<?php echo URLROOT; ?>/pages/terms" class="font-semibold text-brand-navy underline underline-offset-4 hover:text-brand-red">Terms of Service</a>.
</p>
<div class="flex shrink-0 gap-2">
<button id="cookie-decline" type="button" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Dismiss</button>
<button id="cookie-accept"  type="button" class="rounded-md bg-brand-red px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2">Accept Cookies</button>
</div>
</div>
</div>
</div> -->

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/jquery.mask.js"></script>
<script>
(function ($) {
"use strict";

/* Mobile menu toggle */
var $toggle    = $("#menu-toggle");
var $nav       = $("#mobile-menu");
var $iconOpen  = $("#icon-open");
var $iconClose = $("#icon-close");

$toggle.on("click", function () {
var expanded = $toggle.attr("aria-expanded") === "true";
$toggle.attr("aria-expanded", String(!expanded));
$nav.toggleClass("hidden");
$iconOpen.toggleClass("hidden");
$iconClose.toggleClass("hidden");
});

/* ZIP code mask (5-digit numeric) */
$("#zipcode").mask("00000");

/* ZIP → State/City lookup via zippopotam.us (free, no key) */
var zipXhr = null;
$("#zipcode").on("blur", function () {
	var zip = $(this).val().replace(/\D/g, "");
	$("#state").val("");
	$("#city").val("");
	$("#zip-location").addClass("hidden").text("");
	if (zip.length !== 5) { return; }
	if (zipXhr) { zipXhr.abort(); }
	zipXhr = $.ajax({
		url: "https://api.zippopotam.us/us/" + zip,
		method: "GET",
		dataType: "json",
		success: function (data) {
			if (data && data.places && data.places.length) {
				var place = data.places[0];
				$("#state").val(place["state abbreviation"]);
				$("#city").val(place["place name"]);
				$("#zip-location")
					.removeClass("hidden")
					.html('<svg class="inline h-4 w-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>' + place["place name"] + ', ' + place["state abbreviation"]);
			}
		},
		error: function (xhr) {
			if (xhr.statusCode === 0) { return; } /* aborted */
			/* 404 = ZIP not found — silently ignore */
		}
	});
});

/* Availability form validation */
$("#availability-form").validate({
errorElement: "label",
errorClass:   "error",
rules: {
zipcode: { required: true, digits: true, minlength: 5, maxlength: 5 },
email:   { required: true, email: true }
},
messages: {
zipcode: {
required:  "Please enter your ZIP code.",
digits:    "ZIP code must contain numbers only.",
minlength: "ZIP code must be exactly 5 digits.",
maxlength: "ZIP code must be exactly 5 digits."
},
email: {
required: "Please enter your email address.",
email:    "Please enter a valid email address."
}
},
highlight: function (element) {
$(element).addClass("error");
},
unhighlight: function (element) {
$(element).removeClass("error");
},
errorPlacement: function (error, element) {
/* Place error after the icon wrapper div */
error.insertAfter(element.closest(".zip-wrap, .email-wrap") || element);
},
submitHandler: function (form) {
var $form   = $(form);
var $btn    = $form.find('button[type="submit"]');
var $status = $("#form-status");
var origTxt = $btn.text();

$btn.prop("disabled", true).text("Checking...");
$status.addClass("hidden").removeClass("bg-green-50 border-green-200 text-green-700 bg-red-50 border-red-200 text-red-700");

$.ajax({
url:    $form.attr("action"),
method: "POST",
data: {
zipcode:  $("#zipcode").val(),
email:    $("#email").val(),
state:    $("#state").val(),
city:     $("#city").val(),
page_url: window.location.href
},
dataType: "json",
success: function (res) {
$status
.removeClass("hidden")
.addClass("bg-green-50 border-green-200 text-green-700")
.text(res.message || "Thank you! We will be in touch soon.");
$btn.text("Redirecting…").prop("disabled", true);
$form[0].reset();
if (res.redirect_url) {
setTimeout(function () {
window.location.assign(res.redirect_url);
}, 1500);
} else {
$btn.text("Submitted ✓");
}
},
error: function (xhr) {
var msg = "An error occurred. Please try again.";
try { msg = JSON.parse(xhr.responseText).message || msg; } catch (e) {}
$status
.removeClass("hidden")
.addClass("bg-red-50 border-red-200 text-red-700")
.text(msg);
$btn.prop("disabled", false).text(origTxt);
}
});
return false;
}
});

/* Cookie banner */
var COOKIE_KEY = "gfw_cookie_consent_v1";
var $banner    = $("#cookie-banner");
var hasConsent = false;

try { hasConsent = window.localStorage.getItem(COOKIE_KEY) === "accepted"; } catch (e) {}
if (!hasConsent) { $banner.removeClass("hidden"); }

function hideBanner()  { $banner.addClass("hidden"); }
function saveConsent() {
try { window.localStorage.setItem(COOKIE_KEY, "accepted"); } catch (e) {}
hideBanner();
}

$("#cookie-accept").on("click",  saveConsent);
$("#cookie-decline").on("click", hideBanner);

}(jQuery));
</script>
</body>
</html>
