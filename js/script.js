
document.addEventListener('DOMContentLoaded', function() {

    var loginForm = document.querySelector('form[action*="login.php"]');
    if (loginForm) loginForm.onsubmit = function() {
        var username = document.getElementById('username') || document.getElementById('teacher_id') || document.getElementById('student_id') || document.getElementById('parent_id');
        var password = document.getElementById('password');
        if (username && username.value.trim() === '') { alert('Username/ID is required.'); username.focus(); return false; }
        if (password && password.value.trim() === '') { alert('Password is required.'); password.focus(); return false; }
        return true;
    };

    // Person add/edit form (name, email, phone, password)
    var personForm = null;
    if (document.getElementById('name')) personForm = document.getElementById('name').closest('form');
    if (personForm && !personForm.classList.contains('no-validate')) {
        personForm.onsubmit = function() {
            var name = document.getElementById('name');
            var email = document.getElementById('email');
            var phone = document.getElementById('phone');
            var password = document.getElementById('password');
            var confirm = document.getElementById('confirm_password');

            if (name && name.value.trim() === '') { alert('Full name is required.'); name.focus(); return false; }
            if (email && email.value.trim() !== '') {
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!re.test(email.value.trim())) { alert('Enter a valid email.'); email.focus(); return false; }
            }
            if (phone && phone.value.trim() !== '') {
                var ph = phone.value.trim();
                if (!/^[0-9]{10}$/.test(ph)) { alert('Enter a 10-digit phone number.'); phone.focus(); return false; }
            }
            if (password && password.value.trim() !== '') {
                if (password.value.length < 4) { alert('Password must be at least 4 characters.'); password.focus(); return false; }
                if (confirm && password.value !== confirm.value) { alert('Passwords do not match.'); confirm.focus(); return false; }
            } else if (password && password.hasAttribute && password.hasAttribute('required')) {
                if (password.value.trim() === '') { alert('Password is required.'); password.focus(); return false; }
            }
            return true;
        };
    }

    // Fee form (amount, due_date)
    var feeForm = null;
    if (document.getElementById('amount')) feeForm = document.getElementById('amount').closest('form');
    if (feeForm) feeForm.onsubmit = function() {
        var amount = document.getElementById('amount');
        var dueDate = document.getElementById('due_date');
        if (amount) {
            var v = parseFloat(amount.value);
            if (isNaN(v) || v < 1) { alert('Enter a valid amount.'); amount.focus(); return false; }
        }
        if (dueDate && dueDate.value) {
            var sel = new Date(dueDate.value);
            var today = new Date(); today.setHours(0,0,0,0);
            if (sel < today) { alert('Due date cannot be in the past.'); dueDate.focus(); return false; }
        }
        return true;
    };

    // Result form (marks vs total)
    var resultForm = null;
    if (document.getElementById('marks')) resultForm = document.getElementById('marks').closest('form');
    if (resultForm) resultForm.onsubmit = function() {
        var marks = document.getElementById('marks');
        var total = document.getElementById('total_marks');
        if (marks && total) {
            var m = parseFloat(marks.value);
            var t = parseInt(total.value) || 0;
            if (isNaN(m) || m < 0 || (t>0 && m > t)) { alert('Enter valid marks (0 to total).'); marks.focus(); return false; }
            if (isNaN(t) || t < 1) { alert('Enter valid total marks.'); total.focus(); return false; }
        }
        return true;
    };

    // Homework form (title, due date)
    var homeworkForm = null;
    if (document.getElementById('title')) homeworkForm = document.getElementById('title').closest('form');
    if (homeworkForm && document.getElementById('due_date')) homeworkForm.onsubmit = function() {
        var title = document.getElementById('title');
        var dueDate = document.getElementById('due_date');
        if (title && title.value.trim() === '') { alert('Title is required.'); title.focus(); return false; }
        if (dueDate && dueDate.value) {
            var sel = new Date(dueDate.value);
            var today = new Date(); today.setHours(0,0,0,0);
            if (sel < today) { alert('Due date cannot be in the past.'); dueDate.focus(); return false; }
        }
        return true;
    };

    // Password change forms
    var passForm = null;
    if (document.getElementById('current')) passForm = document.getElementById('current').closest('form');
    if (passForm) passForm.onsubmit = function() {
        var newPass = document.getElementById('new');
        var confirm = document.getElementById('confirm');
        if (newPass && newPass.value.length < 4) { alert('Password must be at least 4 characters.'); newPass.focus(); return false; }
        if (newPass && confirm && newPass.value !== confirm.value) { alert('Passwords do not match.'); confirm.focus(); return false; }
        return true;
    };

});