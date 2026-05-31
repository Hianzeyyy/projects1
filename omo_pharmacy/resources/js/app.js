import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    const initAiProcessUsability = () => {
        const aiShell = document.getElementById('ai-process-shell');
        if (!aiShell) {
            return;
        }

        const form = aiShell.querySelector('#ai-process-form');
        const symptomsInput = aiShell.querySelector('#symptoms');
        const symptomsCounter = aiShell.querySelector('#symptoms-counter');
        const symptomChips = aiShell.querySelectorAll('[data-ai-symptom]');
        const successBox = aiShell.querySelector('#ai-ajax-success');
        const errorBox = aiShell.querySelector('#ai-ajax-error');
        const resultContainer = aiShell.querySelector('#ai-result-container');

        const escapeHtml = (value) => {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        };

        const setMessage = (type, message) => {
            if (!successBox || !errorBox) {
                return;
            }

            if (type === 'success') {
                successBox.textContent = message;
                successBox.style.display = 'block';
                errorBox.style.display = 'none';
                errorBox.textContent = '';
                return;
            }

            errorBox.textContent = message;
            errorBox.style.display = 'block';
            successBox.style.display = 'none';
            successBox.textContent = '';
        };

        const renderAiResult = (result) => {
            if (!resultContainer) {
                return;
            }

            const recommendations = Array.isArray(result.recommendations) ? result.recommendations : [];
            const riskFlags = Array.isArray(result.risk_flags) ? result.risk_flags : [];
            const careSteps = Array.isArray(result.care_steps) ? result.care_steps : [];
            const symptomTerms = Array.isArray(result?.summary?.symptom_terms) ? result.summary.symptom_terms : [];
            const noMatchTips = Array.isArray(result.no_match_tips) ? result.no_match_tips : [];

            let recommendationsHtml = '<p>No direct match found based on current symptom keywords. Review manually.</p>';

            if (recommendations.length > 0) {
                const rows = recommendations.map((item) => `
                    <tr>
                        <td>${escapeHtml(item.confidence || 'Low')}</td>
                        <td>${escapeHtml(item.name || '')}</td>
                        <td>${escapeHtml(item.stock ?? '')}</td>
                        <td>PHP ${escapeHtml(item.price || '0.00')}</td>
                        <td>${escapeHtml(item.match_terms || '-')}</td>
                        <td>${escapeHtml(item.reason || '')}</td>
                        <td>${escapeHtml(item.dosage_reminder || '')}</td>
                    </tr>
                `).join('');

                recommendationsHtml = `
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Confidence</th>
                                    <th>Medicine</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Match Terms</th>
                                    <th>Reason</th>
                                    <th>Dosage Reminder</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
            } else if (noMatchTips.length > 0) {
                const tipsHtml = noMatchTips.map((tip) => `<li>${escapeHtml(tip)}</li>`).join('');
                recommendationsHtml += `<ul>${tipsHtml}</ul>`;
            }

            const riskHtml = riskFlags.length > 0
                ? `
                    <h4 style="margin-top:0.75rem;">Risk Flags</h4>
                    <ul>${riskFlags.map((flag) => `<li>${escapeHtml(flag)}</li>`).join('')}</ul>
                `
                : '';

            const careHtml = careSteps.length > 0
                ? `
                    <h4 style="margin-top:0.75rem;">Next Best Actions</h4>
                    <ol>${careSteps.map((step) => `<li>${escapeHtml(step)}</li>`).join('')}</ol>
                `
                : '';

            resultContainer.innerHTML = `
                <h3 class="section-title" style="margin-top:1rem;">AI Output</h3>
                <div class="module-card">
                    <p><strong>Patient:</strong> ${escapeHtml(result.patient || '')}</p>
                    <p><strong>Age:</strong> ${escapeHtml(result.age ?? '')}</p>
                    <p><strong>Analyzed Medicines:</strong> ${escapeHtml(result?.summary?.candidate_count ?? 0)} | <strong>Matches Found:</strong> ${escapeHtml(result?.summary?.match_count ?? 0)}</p>
                    <p><strong>Detected Symptom Terms:</strong> ${escapeHtml(symptomTerms.join(', ') || 'None')}</p>
                    <p><strong>Disclaimer:</strong> ${escapeHtml(result.disclaimer || '')}</p>
                    <h4 style="margin-top:0.75rem;">Recommended Medicines</h4>
                    ${recommendationsHtml}
                    ${riskHtml}
                    ${careHtml}
                </div>
            `;
        };

        const updateCounter = () => {
            if (!symptomsInput || !symptomsCounter) {
                return;
            }

            const len = symptomsInput.value.length;
            symptomsCounter.textContent = `${len} / 1000`;
        };

        if (symptomsInput) {
            symptomsInput.addEventListener('input', updateCounter);
            updateCounter();
        }

        if (form) {
            form.addEventListener('reset', () => {
                window.setTimeout(updateCounter, 0);
            });
        }

        symptomChips.forEach((chip) => {
            chip.addEventListener('click', () => {
                if (!symptomsInput) {
                    return;
                }

                const keyword = chip.getAttribute('data-ai-symptom') || '';
                const current = symptomsInput.value.trim();

                if (keyword.length === 0) {
                    return;
                }

                if (current.length === 0) {
                    symptomsInput.value = keyword;
                } else if (!current.toLowerCase().includes(keyword.toLowerCase())) {
                    symptomsInput.value = `${current}, ${keyword}`;
                }

                symptomsInput.dispatchEvent(new Event('input'));
                symptomsInput.focus();
            });
        });

        if (form && window.axios && form.dataset.ajaxBound !== '1') {
            form.dataset.ajaxBound = '1';

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalLabel = submitBtn ? submitBtn.textContent : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Processing...';
                }

                try {
                    const response = await window.axios.post(form.action, new FormData(form), {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    renderAiResult(response.data.result || {});
                    setMessage('success', 'AI process completed successfully.');
                } catch (error) {
                    if (error.response && error.response.status === 422 && error.response.data && error.response.data.errors) {
                        const entries = Object.values(error.response.data.errors).flat();
                        setMessage('error', entries.length > 0 ? entries[0] : 'Please check form fields and try again.');
                    } else {
                        setMessage('error', 'AI process failed. Please try again.');
                    }
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalLabel;
                    }
                }
            });
        }
    };

    const initReportsAjax = () => {
        const reportsShell = document.getElementById('reports-shell');
        if (!reportsShell || !window.axios) {
            return;
        }

        const setMessage = (type, message) => {
            const successBox = document.getElementById('report-ajax-success');
            const errorBox = document.getElementById('report-ajax-error');
            if (!successBox || !errorBox) {
                return;
            }

            if (type === 'success') {
                successBox.textContent = message;
                successBox.style.display = 'block';
                errorBox.style.display = 'none';
                errorBox.textContent = '';
                return;
            }

            errorBox.textContent = message;
            errorBox.style.display = 'block';
            successBox.style.display = 'none';
            successBox.textContent = '';
        };

        const setLoading = (form, loadingText) => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn) {
                return () => {};
            }

            const original = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = loadingText;

            return () => {
                submitBtn.disabled = false;
                submitBtn.textContent = original;
            };
        };

        const replaceSection = (doc, selector) => {
            const current = document.querySelector(selector);
            const incoming = doc.querySelector(selector);
            if (!current || !incoming) {
                return;
            }

            current.replaceWith(incoming);
        };

        const bindOnce = (selector, handler) => {
            const node = document.querySelector(selector);
            if (!node || node.dataset.ajaxBound === '1') {
                return;
            }
            node.dataset.ajaxBound = '1';
            node.addEventListener('submit', handler);
        };

        const fetchAndRefresh = async (url) => {
            const response = await window.axios.get(url, {
                headers: {
                    Accept: 'text/html',
                },
            });

            const parser = new DOMParser();
            const doc = parser.parseFromString(response.data, 'text/html');

            replaceSection(doc, '#report-filter-form');
            replaceSection(doc, '#report-download-form');
            replaceSection(doc, '#report-archive-form');
            replaceSection(doc, '#report-metrics-section');
            replaceSection(doc, '#receipt-filter-form');
            replaceSection(doc, '#receipt-history-table-section');

            const cleanUrl = url.includes('?') ? url : `${url}?`;
            window.history.replaceState({}, '', cleanUrl.replace(/\?$/, ''));
            initHandlers();
        };

        const initHandlers = () => {
            bindOnce('#report-download-form', async function (event) {
                event.preventDefault();
                const release = setLoading(this, 'Exporting...');

                try {
                    const response = await window.axios.post(this.action, new FormData(this), {
                        responseType: 'blob',
                        headers: {
                            Accept: 'application/pdf',
                        },
                    });

                    const disposition = response.headers['content-disposition'] || '';
                    const match = disposition.match(/filename="?([^";]+)"?/i);
                    const fileName = match && match[1] ? match[1] : 'report.pdf';
                    const blobUrl = URL.createObjectURL(response.data);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = fileName;
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    URL.revokeObjectURL(blobUrl);

                    setMessage('success', 'Report exported successfully. Download started.');
                } catch (error) {
                    setMessage('error', 'Failed to export report PDF. Please try again.');
                } finally {
                    release();
                }
            });

            bindOnce('#report-archive-form', async function (event) {
                event.preventDefault();
                const release = setLoading(this, 'Saving...');

                try {
                    const response = await window.axios.post(this.action, new FormData(this), {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    const message = response.data && response.data.message
                        ? response.data.message
                        : 'Report archived successfully.';
                    setMessage('success', message);
                } catch (error) {
                    let message = 'Failed to archive report PDF. Please try again.';
                    if (error.response && error.response.data && error.response.data.message) {
                        message = error.response.data.message;
                    }
                    setMessage('error', message);
                } finally {
                    release();
                }
            });

            bindOnce('#report-filter-form', async function (event) {
                event.preventDefault();
                const release = setLoading(this, 'Loading...');

                try {
                    const query = new URLSearchParams(new FormData(this)).toString();
                    await fetchAndRefresh(`${this.action}?${query}`);
                    setMessage('success', 'Report view updated.');
                } catch (error) {
                    setMessage('error', 'Failed to refresh report view. Please try again.');
                } finally {
                    release();
                }
            });

            bindOnce('#receipt-filter-form', async function (event) {
                event.preventDefault();
                const release = setLoading(this, 'Filtering...');

                try {
                    const query = new URLSearchParams(new FormData(this)).toString();
                    await fetchAndRefresh(`${this.action}?${query}`);
                    setMessage('success', 'Receipt history updated.');
                } catch (error) {
                    setMessage('error', 'Failed to refresh receipt history. Please try again.');
                } finally {
                    release();
                }
            });
        };

        initHandlers();
    };

    // Multi-button portal navbar dropdown behavior (menu + account + submenu)
    const portalDropdownGroups = document.querySelectorAll('[data-portal-dropdown-group]');

    const closeDropdownGroup = (group) => {
        const toggle = group.querySelector('[data-portal-dropdown-toggle]');
        const panel = group.querySelector('[data-portal-dropdown-panel]');
        if (!toggle || !panel) {
            return;
        }

        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');

        const submenuToggle = group.querySelector('[data-portal-submenu-toggle]');
        const submenuPanel = group.querySelector('[data-portal-submenu-panel]');
        if (submenuToggle && submenuPanel) {
            submenuPanel.hidden = true;
            submenuToggle.setAttribute('aria-expanded', 'false');
        }
    };

    const closeAllDropdownGroups = () => {
        portalDropdownGroups.forEach((group) => closeDropdownGroup(group));
    };

    if (portalDropdownGroups.length > 0) {
        portalDropdownGroups.forEach((group) => {
            const toggle = group.querySelector('[data-portal-dropdown-toggle]');
            const panel = group.querySelector('[data-portal-dropdown-panel]');
            const submenuToggle = group.querySelector('[data-portal-submenu-toggle]');
            const submenuPanel = group.querySelector('[data-portal-submenu-panel]');

            if (!toggle || !panel) {
                return;
            }

            panel.hidden = true;

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const shouldOpen = panel.hidden;
                closeAllDropdownGroups();
                if (shouldOpen) {
                    panel.hidden = false;
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            panel.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            if (submenuToggle && submenuPanel) {
                submenuPanel.hidden = true;
                submenuToggle.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const shouldOpenSubmenu = submenuPanel.hidden;
                    submenuPanel.hidden = !shouldOpenSubmenu;
                    submenuToggle.setAttribute('aria-expanded', shouldOpenSubmenu ? 'true' : 'false');
                });
            }
        });

        document.addEventListener('click', closeAllDropdownGroups);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAllDropdownGroups();
            }
        });
    }

    // Shared sidebar toggle behavior for dashboard and records/management pages
    if (body && (body.classList.contains('dashboard-page') || body.classList.contains('app-shell'))) {
        const toggleButton = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('[data-sidebar]');
        const overlay = document.querySelector('[data-sidebar-overlay]');

        const closeSidebar = () => {
            body.classList.remove('sidebar-open');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', 'false');
            }
        };

        const openSidebar = () => {
            body.classList.add('sidebar-open');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', 'true');
            }
        };

        if (toggleButton && sidebar && overlay) {
            toggleButton.setAttribute('aria-expanded', 'false');

            toggleButton.addEventListener('click', () => {
                if (body.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) {
                    closeSidebar();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        }
    }

    // Centralized delete confirmation for records pages
    document.querySelectorAll('[data-confirm-delete]').forEach((button) => {
        button.addEventListener('click', (event) => {
            const message = button.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this record?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    initReportsAjax();
    initAiProcessUsability();
});
