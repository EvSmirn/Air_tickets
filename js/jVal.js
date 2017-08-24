$(function(){
	$('#form').validate({
		rules:{

            login:{
                required: true,
                minlength: 2,
                   },
                     password:{
                required: true,
             
                   },

            surename:{
                required: true,
                minlength: 2,
                    },
            name:{
                required: true,
                minlength: 2,
                    },
            middle_name:{
                required: true,
                minlength: 2,
                    },
             issued_by:{
                required: true,
                        },
              phone_number:{
                required: true,
              
                    },
                     address:{
                required: true,
                
                    },
                    
            e_mail:{
                required: true,
                email: true,
                    },
            passport_ID:{
                required: true,
                digits: true,
                    },
       },
                messages:{

            login:{
                required: "Это поле обязательно для заполнения",
                minlength: "Логин должен быть минимум 2 символа",
       
            },
             password:{
                required: "Это поле обязательно для заполнения",
       
       
            },

            surename:{
                required: "Это поле обязательно для заполнения",
                minlength: "Фамилия должена быть минимум 2 символа",
                     },
            name:{
                required: "Это поле обязательно для заполнения",
                minlength: "Имя должено быть минимум 2 символа",
            
            },
             
            middle_name:{
                required: "Это поле обязательно для заполнения",
                minlength: "Отчество должено быть минимум 2 символа",
                     },
                           issued_by:{
                required: "Это поле обязательно для заполнения",
                                    },
                                          phone_number:{
                required: "Это поле обязательно для заполнения",
                                  },
                                        address:{
                required: "Это поле обязательно для заполнения",
            
                     },
            e_mail:{
                required: "Это поле обязательно для заполнения",
                email: "Введите корректно адрес электронной почты",
                     },
            passport_ID:{
                required: "Это поле обязательно для заполнения",
                digits: "Номер паспорта может содержать только числа",
                     },

       

       }

       
	});
});
	
        

