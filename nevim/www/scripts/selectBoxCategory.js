$(document).ready(function () {
   const selectCategory = $('#selectCategory');
   const selectSubcategory = $('#selectSubcategory');
   const selectSubsubcategory = $('#selectSubsubcategory');
   selectCategory.on('change', () => handleSelectBox(selectCategory, selectSubcategory));
   selectSubcategory.on('change', () => handleSelectBox(selectSubcategory, selectSubsubcategory));
});
function handleSelectBox(source, target){
   target.empty();
   target.append('<option value="">Zvolte kategorii</option>');
   if (source.val() !== "") {
      $.ajax({
         url: 'https://nevim.local/adverts/advert/categories?superordinate=' + source.val(),
         method: 'GET',
         success: function (response) {
            for (let [id, text] of Object.entries(response)) {
               target.append(`<option value=${id}>${text}</option>`);
            }
         },
      });
   }
}

