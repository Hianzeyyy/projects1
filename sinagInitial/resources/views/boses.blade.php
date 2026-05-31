@extends('layouts.app')

@section('content')
<style>
  .chip { border-radius: 1rem; padding: 0.35rem 1.1rem; font-weight: 500; margin-right: 0.5rem; }
  .chip.active { background: #16c784; color: #fff; }
  .chip:not(.active) { background: #f3f3f3; color: #222; }
  .modal-backdrop { z-index: 1040 !important; }
  .modal-content { border-radius: 1.5rem; }
</style>
<div class="container py-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
          <h4 class="fw-bold mb-0"><i class="fa-regular fa-comment-dots text-success me-2"></i>Boses-Isolan</h4>
          <div class="text-muted small">Voice out your concerns safely. Community-driven campus improvements.</div>
        </div>
        <button class="btn btn-success fw-semibold px-4 py-2 shadow-sm" style="border-radius:0.8rem;" data-bs-toggle="modal" data-bs-target="#suggestionModal"><i class="fa-solid fa-plus me-2"></i>Submit Suggestion</button>
      </div>
      <div class="mb-3 d-flex align-items-center gap-2">
        <div class="input-group" style="max-width: 400px;">
          <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" class="form-control border-start-0" placeholder="Search for suggestions...">
        </div>
        <button class="btn btn-outline-secondary ms-2" style="border-radius:0.8rem;"><i class="fa-solid fa-filter"></i></button>
      </div>
      <div class="mb-4">
        <span class="chip active">All</span>
        <span class="chip">Infrastructure</span>
        <span class="chip">Security</span>
        <span class="chip">Maintenance</span>
        <span class="chip">Policy</span>
      </div>
      <!-- Suggestions list would go here -->
    </div>
    <!-- Suggestion Modal -->
    <div class="modal fade" id="suggestionModal" tabindex="-1" aria-labelledby="suggestionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">
        <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="suggestionModalLabel"><i class="fa-regular fa-comment-dots text-success me-2"></i>Submit a Suggestion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form>
        <div class="modal-body pt-0">
          <div class="mb-3">
          <label class="form-label fw-semibold">Title *</label>
          <input type="text" class="form-control" placeholder="E.g. Install better lighting in parking area" maxlength="100" required>
          <div class="form-text">0/100 characters</div>
          </div>
          <div class="mb-3">
            <!-- ...existing modal content... -->
          </div>
        </div>
        </form>
      </div>
      </div>
    </div>
  </main>
</div>
@endsection
            <label class="form-label fw-semibold">Description *</label>
            <textarea class="form-control" rows="3" placeholder="Describe your suggestion in detail. Include why it's important and how it would help the campus community." required></textarea>
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Location *</label>
              <input type="text" class="form-control" placeholder="E.g. Main Building, Library" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Category *</label>
              <select class="form-select" required>
                <option>Infrastructure</option>
                <option>Security</option>
                <option>Maintenance</option>
                <option>Policy</option>
              </select>
            </div>
          </div>
          <div class="alert alert-success mt-3 py-2 small" style="border-radius:0.7rem;">
            <i class="fa-regular fa-user-secret me-1"></i> Your suggestion will be posted under your alias <b>Hidden-Falcon-92</b> and reviewed by the admin team.
          </div>
        </div>
        <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary px-4" style="border-radius:0.8rem;">Cancel</button>
          <button type="submit" class="btn btn-success fw-semibold px-4" style="border-radius:0.8rem;"><i class="fa-solid fa-paper-plane me-2"></i>Submit Suggestion</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
