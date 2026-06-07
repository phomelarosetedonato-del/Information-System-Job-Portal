<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile Photo Preview & File Size Check (7MB limit)
    const profilePhotoInput = document.getElementById('profile_photo');
    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 7 * 1024 * 1024; // 7MB
                const previewLimit = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('File size must be less than 7MB. Please choose a smaller file.');
                    this.value = '';
                    return;
                }
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP).');
                    this.value = '';
                    return;
                }
                if (file.size > previewLimit) {
                    // No preview for large files, but allow upload
                    const previewMsg = document.getElementById('profile-photo-preview-msg');
                    if (previewMsg) {
                        previewMsg.textContent = 'Preview is disabled for images larger than 2MB, but your file will still be uploaded.';
                        previewMsg.style.display = 'block';
                    }
                    return;
                } else {
                    const previewMsg = document.getElementById('profile-photo-preview-msg');
                    if (previewMsg) {
                        previewMsg.textContent = '';
                        previewMsg.style.display = 'none';
                    }
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentPhoto = document.querySelector('.rounded-circle') || document.querySelector('[width="120"]');
                    if (currentPhoto) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Photo Preview';
                        img.className = 'rounded-circle border shadow-sm mb-3';
                        img.style.width = '120px';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';
                        img.style.border = '4px solid #e9ecef';
                        currentPhoto.parentNode.replaceChild(img, currentPhoto);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // PWD ID Photo Preview & File Size Check (7MB limit)
    const pwdIdPhotoInput = document.getElementById('pwd_id_photo');
    if (pwdIdPhotoInput) {
        pwdIdPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 7 * 1024 * 1024; // 7MB
                if (file.size > maxSize) {
                    alert('PWD ID Photo file size must be less than 7MB.');
                    this.value = '';
                    return;
                }
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP).');
                    this.value = '';
                    return;
                }
                // Only preview if file is < 2MB to avoid browser freeze
                if (file.size <= 2 * 1024 * 1024) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add preview logic here if you have a preview element
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }
    // Handle Skills dropdown "Others" option
    const skillsSelect = document.getElementById('skills');
    const skillsOtherContainer = document.getElementById('skills_other_container');
    const skillsOtherInput = document.getElementById('skills_other');
    function toggleSkillsOther() {
        if (skillsSelect && skillsSelect.value === 'Others') {
            skillsOtherContainer.style.opacity = '0';
            skillsOtherContainer.style.display = 'block';
            setTimeout(() => { skillsOtherContainer.style.opacity = '1'; }, 10);
            skillsOtherInput.required = true;
            skillsOtherInput.focus();
        } else {
            skillsOtherContainer.style.opacity = '0';
            setTimeout(() => { skillsOtherContainer.style.display = 'none'; }, 300);
            skillsOtherInput.required = false;
            skillsOtherInput.value = '';
        }
    }
    if (skillsSelect) {
        if (skillsSelect.value === 'Others') {
            skillsOtherContainer.style.display = 'block';
            skillsOtherContainer.style.opacity = '1';
            skillsOtherInput.required = true;
        }
        skillsSelect.addEventListener('change', toggleSkillsOther);
    }
    // Handle Qualifications dropdown "Others" option
    const qualificationsSelect = document.getElementById('qualifications');
    const qualificationsOtherContainer = document.getElementById('qualifications_other_container');
    const qualificationsOtherInput = document.getElementById('qualifications_other');
    function toggleQualificationsOther() {
        if (qualificationsSelect && qualificationsSelect.value === 'Others') {
            qualificationsOtherContainer.style.opacity = '0';
            qualificationsOtherContainer.style.display = 'block';
            setTimeout(() => { qualificationsOtherContainer.style.opacity = '1'; }, 10);
            qualificationsOtherInput.required = true;
            qualificationsOtherInput.focus();
        } else {
            qualificationsOtherContainer.style.opacity = '0';
            setTimeout(() => { qualificationsOtherContainer.style.display = 'none'; }, 300);
            qualificationsOtherInput.required = false;
            qualificationsOtherInput.value = '';
        }
    }
    if (qualificationsSelect) {
        if (qualificationsSelect.value === 'Others') {
            qualificationsOtherContainer.style.display = 'block';
            qualificationsOtherContainer.style.opacity = '1';
            qualificationsOtherInput.required = true;
        }
        qualificationsSelect.addEventListener('change', toggleQualificationsOther);
    }
    // Handle Special Needs dropdown "Others" option
    const specialNeedsSelect = document.getElementById('special_needs');
    const specialNeedsOtherContainer = document.getElementById('special_needs_other_container');
    const specialNeedsOtherInput = document.getElementById('special_needs_other');
    function toggleSpecialNeedsOther() {
        if (specialNeedsSelect && specialNeedsSelect.value === 'Others') {
            specialNeedsOtherContainer.style.opacity = '0';
            specialNeedsOtherContainer.style.display = 'block';
            setTimeout(() => { specialNeedsOtherContainer.style.opacity = '1'; }, 10);
            specialNeedsOtherInput.required = true;
            specialNeedsOtherInput.focus();
        } else {
            specialNeedsOtherContainer.style.opacity = '0';
            setTimeout(() => { specialNeedsOtherContainer.style.display = 'none'; }, 300);
            specialNeedsOtherInput.required = false;
            specialNeedsOtherInput.value = '';
        }
    }
    if (specialNeedsSelect) {
        if (specialNeedsSelect.value === 'Others') {
            specialNeedsOtherContainer.style.display = 'block';
            specialNeedsOtherContainer.style.opacity = '1';
            specialNeedsOtherInput.required = true;
        }
        specialNeedsSelect.addEventListener('change', toggleSpecialNeedsOther);
    }
    // Form validation and submission
    const form = document.getElementById('profileUpdateForm');
    const submitBtn = document.getElementById('submitBtn');
    if (form && submitBtn) {
        submitBtn.disabled = false;
        submitBtn.removeAttribute('disabled');
        submitBtn.style.pointerEvents = 'auto';
        submitBtn.style.cursor = 'pointer';
        submitBtn.style.opacity = '1';
        const resetBtn = form.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = window.location.pathname;
            });
        }
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            console.log('Current form action:', this.action);
            console.log('Current form method:', this.method);
            // Handle "Others" options for skills
            if (skillsSelect && skillsSelect.value === 'Others' && skillsOtherInput && skillsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'skills';
                hiddenInput.value = skillsOtherInput.value.trim();
                form.appendChild(hiddenInput);
            }
            // Handle "Others" options for qualifications
            if (qualificationsSelect && qualificationsSelect.value === 'Others' && qualificationsOtherInput && qualificationsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'qualifications';
                hiddenInput.value = qualificationsOtherInput.value.trim();
                form.appendChild(hiddenInput);
            }
            // Handle "Others" options for special needs
            if (specialNeedsSelect && specialNeedsSelect.value === 'Others' && specialNeedsOtherInput && specialNeedsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'special_needs';
                hiddenInput.value = specialNeedsOtherInput.value.trim();
                form.appendChild(hiddenInput);
            }
            // Always re-enable selects after submission attempt
            if (skillsSelect) skillsSelect.disabled = false;
            if (qualificationsSelect) qualificationsSelect.disabled = false;
            if (specialNeedsSelect) specialNeedsSelect.disabled = false;
            // Validate only VISIBLE required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            let invalidFields = [];
            console.log('Total required fields found:', requiredFields.length);
            requiredFields.forEach(field => {
                console.log('Checking field:', field.name || field.id, 'Type:', field.type, 'Value:', field.value);
                if (field.type === 'hidden' || field.disabled) return;
                const container = field.closest('.col-12, .col-md-6, div[id$="_container"]');
                if (container && window.getComputedStyle(container).display === 'none') return;
                if (field.offsetParent === null) return;
                const value = field.value ? field.value.trim() : '';
                if (!value) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    invalidFields.push(field.name || field.id);
                    if (!firstInvalidField) firstInvalidField = field;
                    console.log('Field INVALID:', field.name || field.id);
                } else {
                    field.classList.remove('is-invalid');
                    console.log('Field VALID:', field.name || field.id);
                }
            });
            console.log('Form validation result:', isValid, 'Invalid fields:', invalidFields);
            if (!isValid) {
                e.preventDefault();
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstInvalidField.focus(), 300);
                }
                alert('Please fill in all required fields marked with *\n\nMissing fields: ' + invalidFields.join(', '));
                if (skillsSelect) skillsSelect.disabled = false;
                if (qualificationsSelect) qualificationsSelect.disabled = false;
                if (specialNeedsSelect) specialNeedsSelect.disabled = false;
                return false;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> <span class="d-none d-sm-inline">Updating Profile...</span><span class="d-inline d-sm-none">Updating...</span>';
            const allButtons = form.querySelectorAll('button');
            allButtons.forEach(btn => { if (btn !== submitBtn) btn.disabled = true; });
            return true;
        });
        const allInputs = form.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            ['input', 'change'].forEach(event => {
                input.addEventListener(event, function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
        submitBtn.addEventListener('click', function(e) {});
    } else {
        console.error('Form or submit button not found');
    }
});
</script>
