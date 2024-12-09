const checkboxes = document.querySelectorAll('.performanceTaskTracker_table_container tbody input[type="checkbox"]');
const overallGradeCell = document.getElementById('overall-grade');

function calculateOverallGrade() {
  let totalPoints = 0;
  let totalWeight = 0;

  checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
      const weight = parseInt(checkbox.dataset.weight);
      totalPoints += weight;
      totalWeight += weight;
    }
  });

  const overallGrade = (totalPoints / totalWeight) * 100;
  overallGradeCell.textContent = `Overall Grade: ${overallGrade.toFixed(2)}%`;
}

checkboxes.forEach(checkbox => {
  checkbox.addEventListener('change', calculateOverallGrade);
});

// Call calculateOverallGrade initially (optional)
calculateOverallGrade();