const toggleSidebar = () => {
  const sidebar = document.getElementById('sidebar');
  if (sidebar.style.display === "none") {
    sidebar.style.display = "block";
  } else {
    sidebar.style.display = "none";
  }
}