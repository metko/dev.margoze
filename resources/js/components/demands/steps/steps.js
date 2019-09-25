let steps =  [
   { 
      id: 1, validated : false,
      fields : [
         { name : 'title', value :'', validated : false, errorMessage: "Doit contenir au minimum 6 caractéres" }
      ]
    },
   { 
      id: 2, validated : false,
      fields: [
         { name: 'category_id', value: '', validated: false, errorMessage: 'Selectionner une categorie', data: {}},
         // { name: 'sub_category_id', value: '', validated: false, errorMessage: '', data: {id:'', name:''}},
         { name: 'description', value: '', validated: false, errorMessage: 'La description doit contenir au minimum 30 caractéres'},
         { name: 'content', value: '', validated: false, errorMessage: 'La description doit contenir au minimum 100 caractéres'}
      ]
   },
   { 
      id: 3, validated : false ,
      fields: [
            { name: 'commune_id', value: '', validated: false, errorMessage: 'Selectionner une commune', data: {}},
            { name: 'district_id', value: '', validated: false, errorMessage: 'Selectionner un Quartier', data: {}},
            { name: 'address_1', value: '', validated: false, errorMessage: "Saisissez l'adresse ou le service sera executé"},
            { name: 'address_2', value: '', validated: true, errorMessage: ""},
            { name: 'be_done_at', value: '', validated: false, errorMessage: 'Choisissez une date'}
         ]
   },
   { id: 4, validated : true },
   { id: 5, validated : false,
      fields: [
         { name: 'conditions', value: '', validated: false, errorMessage: 'Vous devez acceptez les conditions'},
      ]
   },
] 

export default steps;
