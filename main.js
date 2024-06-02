// show save button when focus
let editTask = document.querySelectorAll(".editTask");
let editTaskText = document.querySelectorAll(".editTask [type='text']");
let editTaskSave = document.querySelectorAll(".editTask [type='submit']");
editTaskText.forEach((e, i) => {
  e.onfocus = () => {
    editTaskSave[i].removeAttribute("hidden");
  };
});
// done task
let checkBoxForm = document.querySelectorAll(".check_box");
let checkBox = document.querySelectorAll(".check_box [type='checkbox']");
let checkBoxSave = document.querySelectorAll(".check_box [type='submit']");
checkBox.forEach((e, i) => {
  e.onchange = () => {
    e.checked === true ? (e.value = "1") : (e.value = "0");
    checkBoxSave[i].click();
  };
});
