<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title>Risk Alert System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php $profilesList = collect($profiles ?? [])->all(); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h1 class="h4 mb-1">Risk Alert System</h1>
                        <p class="text-muted mb-3">Check allergy matches, drug interactions, and dosage safety before dispensing.</p>

                        <form id="risk-check-form" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="patient_profile_id">Patient Profile</label>
                                <select id="patient_profile_id" class="form-select" required>
                                    <option value="">Select profile</option>
                                    <?php foreach ($profilesList as $profile): ?>
                                        <?php $profileRow = (array) $profile; ?>
                                        <option value="<?= e((string) ($profileRow['id'] ?? '')) ?>">
                                            Profile #<?= e((string) ($profileRow['id'] ?? '')) ?> (Age: <?= e((string) ($profileRow['age'] ?? '')) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="new_drug_name">New Drug Name</label>
                                <input id="new_drug_name" class="form-control" type="text" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="new_drug_ingredients">New Drug Ingredients (comma-separated)</label>
                                <input id="new_drug_ingredients" class="form-control" type="text" placeholder="e.g. amoxicillin, clavulanic acid" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="current_medication_ingredients">Current Medication Ingredients (comma-separated)</label>
                                <input id="current_medication_ingredients" class="form-control" type="text" placeholder="e.g. warfarin, ibuprofen">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="requested_dosage_mg">Requested Dosage (mg)</label>
                                <input id="requested_dosage_mg" class="form-control" type="number" min="0" step="0.01">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Run Risk Assessment</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="risk-result" class="card shadow-sm d-none">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Assessment Result</h2>
                        <p id="risk-summary" class="mb-3"></p>

                        <div id="risk-alerts-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('risk-check-form');
        const resultCard = document.getElementById('risk-result');
        const summary = document.getElementById('risk-summary');
        const alertsWrapper = document.getElementById('risk-alerts-wrapper');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const splitList = (value) => value
            .split(',')
            .map(item => item.trim())
            .filter(Boolean);

        const statusClass = (status) => {
            if (status === 'critical') return 'danger';
            if (status === 'warning') return 'warning';
            return 'success';
        };

        const statusText = (status) => {
            if (status === 'critical') return 'Critical / Blocked';
            if (status === 'warning') return 'Warning';
            return 'Safe';
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const payload = {
                patient_profile_id: Number(document.getElementById('patient_profile_id').value),
                new_drug_name: document.getElementById('new_drug_name').value,
                new_drug_ingredients: splitList(document.getElementById('new_drug_ingredients').value),
                current_medication_ingredients: splitList(document.getElementById('current_medication_ingredients').value),
                requested_dosage_mg: document.getElementById('requested_dosage_mg').value || null,
            };

            const response = await fetch('<?= e(route('risk-alerts.assess', [], false)) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (!response.ok || !data.ok) {
                summary.textContent = 'Unable to run risk assessment. Please review your input.';
                alertsWrapper.innerHTML = '<div class="alert alert-danger">Request failed.</div>';
                resultCard.classList.remove('d-none');
                return;
            }

            const checks = data.data.checks;
            summary.textContent = data.data.summary;

            if (data.rendered_alerts) {
                alertsWrapper.innerHTML = [
                    data.rendered_alerts.allergy_match || '',
                    data.rendered_alerts.drug_interaction || '',
                    data.rendered_alerts.dosage_safety || '',
                ].join('');
            } else {
                alertsWrapper.innerHTML = [
                    ['Allergy Match', checks.allergy_match],
                    ['Drug-Drug Interaction', checks.drug_interaction],
                    ['Dosage Safety', checks.dosage_safety],
                ].map(([title, check]) => `
                    <div class="alert alert-${statusClass(check.status)} border">
                        <h6 class="mb-1">${title}: <strong>${statusText(check.status)}</strong></h6>
                        <p class="mb-0">${check.message}</p>
                    </div>
                `).join('');
            }

            resultCard.classList.remove('d-none');
        });
    </script>
</body>

</html>
