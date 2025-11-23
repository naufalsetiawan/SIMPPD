<footer class="bg-gray-100 flex justify-ceenter sm:ml-64 relative">
    <span class="px-8 py-1 text-center text-sm text-gray-600">
        &copy; <span id="year"></span> Balai Monitor SFR Kelas I Semarang. All rights reserved.
    </span>
</footer>

<script>
    const d = new Date();
    let year = d.getFullYear();
    document.getElementById("year").innerHTML = year;
</script>