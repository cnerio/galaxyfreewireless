<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Thank You | American Assist</title>
	<!-- <link rel="icon" type="image/svg+xml" href="<?php //echo URLROOT; ?>/public/img/favicon.svg"> -->
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo URLROOT; ?>/public/img/favicon.png">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
	<main class="mx-auto flex min-h-screen max-w-3xl items-center justify-center px-4 py-12">
		<section class="w-full rounded-2xl bg-white p-8 shadow-lg sm:p-10">
			<div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
				<svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
				</svg>
			</div>
			<h1 class="mt-6 text-center text-3xl font-bold text-slate-900">Thank you for your submission</h1>
			<p class="mt-3 text-center text-base text-slate-600">Your enrollment request was received successfully. A team member will contact you soon to continue the process.</p>
			<?php //if (!empty($data['customer_id'])) : ?>
				<!-- <p class="mt-3 text-center text-sm text-slate-500">You can upload your ID and Proof of Benefit now or come back later using your customer ID.</p> -->
			<?php //endif; ?>
			<div class="mt-8 text-center">
				<!-- <?php //if (!empty($data['customer_id'])) : ?>
					<a href="<?php //echo URLROOT; ?>/enrolls/getdocuments/<?php //echo urlencode($data['customer_id']); ?>" class="inline-flex items-center rounded-md bg-brand-red px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Upload Documents Now</a>
				<?php //endif; ?> -->
				<a href="<?php echo URLROOT; ?>" class="ml-2 inline-flex items-center rounded-md bg-[#003366] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#02284d]">Return to home</a>
			</div>
		</section>
	</main>
</body>
</html>
