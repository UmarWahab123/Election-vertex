<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create full page entries</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script>$.fn.setUrduInput=function(t){var n={q:"ق",w:"و",e:"ع",r:"ر",t:"ت",y:"ے",u:"ء",i:"ی",o:"ہ",p:"پ",a:"ا",s:"س",d:"د",f:"ف",g:"گ",h:"ح",j:"ج",k:"ک",l:"ل",z:"ز",x:"ش",c:"چ",v:"ط",b:"ب",n:"ن",m:"م","`":"ً",",":"،",".":"۔",Q:"ْ",W:"ّ",E:"ٰ",R:"ڑ",T:"ٹ",Y:"َ",U:"ئ",I:"ِ",O:"ۃ",P:"ُ",A:"آ",S:"ص",D:"ڈ",G:"غ",H:"ھ",J:"ض",K:"خ",Z:"ذ",X:"ژ",C:"ث",V:"ظ",N:"ں",M:"٘","~":"ٍ","?":"؟",F:"ٔ",L:"ل",B:"ب"},i={0:"۰",1:"۱",2:"۲",3:"۳",4:"۴",5:"۵",6:"۶",7:"۷",8:"۸",9:"۹"};t&&t.urduNumerals&&$.extend(n,i);var s="";$(this).bind("input",function(){var t=$(this)[0].selectionEnd,i=$(this).val(),e=t==i.length;if(s!=i){for(var a=[],h=0;h<i.length;h++){var r=i.charAt(h);a.push(n[r]||r)}$(this).val(a.join("")),s=$(this).val(),e||($(this)[0].selectionStart=$(this)[0].selectionEnd=t)}})};</script>
    <style>
        .list-item {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .field_input {
            padding: 10px;
            height: 20px;
            border: 2px solid #222;
        }
        .field_input.error {
            border: 2px solid #f00;
        }
        .container {
            display: flex;
        }
        .container > * {
            flex: 0 0 50%;
        }
        .field-wrapper.btn_wrapper {
            flex: 1 1 100%;
        }
        .action_btn {
            padding: 10px 30px;
            margin: 20px;
            background-color: #007aff;
            color: #fff;
            border: 1px solid #007add;
            border-radius: 50px;
        }
        .btn_wrapper button {
            padding: 10px 30px;
            margin: 3px;
            background-color: #ddd;
            border: 1px solid #aaa;
            border-radius: 5px;
        }
        .num {
            flex: 1 1 100%;
            font-size: 120%;
        }
        .image_wrapper {
            position: relative;
        }
        .image_wrapper img {
            height: 95vh;
            margin: 0 auto;
            display: block;
            position: fixed;
            top: 10px;
            width: 45vw;
        }
        .btn_wrapper .success {
            display: inline-block;
            padding: 10px 30px;
            background: #00bf00;
            color: #FFF;
            margin: 5px;
            border-radius: 1px;
        }
        .keyboard_img_wrapper {
            position: fixed;
            top: 10px;
        }
        .btns_wrapper_rows {
            margin-top: 320px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="image_wrapper">
                <img src="{{ $firebaseUrl->image_url }}" alt="">
            </div>
        </div>
        <div class="right">
            <div class="keyboard_img_wrapper">
                <img src="https://firebasestorage.googleapis.com/v0/b/one-call-59851.appspot.com/o/images%2F1629473475887.png?alt=media&token=7fd31583-ac03-4943-9e0b-6b2a85fcb266" style="width:100%">
            </div>
            <div class="btns_wrapper_rows">
                <button class="action_btn" id="btn_add_row">Add Row</button>
                <button class="action_btn" id="btn_add_5_rows">Add 5 Rows</button>
                <button class="action_btn" id="btn_add_10_rows">Add 10 Rows</button>
                <button class="action_btn" id="btn_add_30_rows">Add 30 Rows</button>
            </div>
            <div class="list-items">
                <div class="list-item" id="list-item-0">
                    <div class="num">1.</div>
                    <div class="field-wrapper serial_no">
                        <input type="text" class="field_input field_serial_no" placeholder="سیریل نمبر" dir="ltr">
                    </div>
                    <div class="field-wrapper family_no">
                        <input type="text" class="field_input field_family_no" placeholder="فیملی نمبر" dir="ltr">
                    </div>
                    <div class="field-wrapper first_name">
                        <input type="text" class="field_input field_first_name" placeholder="نام" dir="rtl">
                    </div>
                    <div class="field-wrapper parent_name">
                        <input type="text" class="field_input field_parent_name" placeholder="باپ / شوہر" dir="rtl">
                    </div>
                    <div class="field-wrapper age">
                        <input type="text" class="field_input field_age" placeholder="عمر" dir="ltr">
                    </div>
                    <div class="field-wrapper cnic">
                        <input type="text" class="field_input field_cnic" placeholder="شناختی کارڈ" dir="ltr">
                    </div>
                    <div class="field-wrapper address">
                        <input type="text" class="field_input field_address" placeholder="پتہ" dir="rtl">
                    </div>
                    <div class="field-wrapper btn_wrapper">
                        <button>Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const getListItemHtml = (options) => {
            return `
                <div class="num">${options.i}. </div>
                <div class="field-wrapper serial_no">
                    <input type="text" class="field_input field_serial_no" placeholder="سیریل نمبر" dir="ltr">
                </div>
                <div class="field-wrapper family_no">
                    <input type="text" class="field_input field_family_no" placeholder="فیملی نمبر" dir="ltr">
                </div>
                <div class="field-wrapper first_name">
                    <input type="text" class="field_input field_first_name" placeholder="نام" dir="rtl">
                </div>
                <div class="field-wrapper parent_name">
                    <input type="text" class="field_input field_parent_name" placeholder="باپ / شوہر" dir="rtl">
                </div>
                <div class="field-wrapper age">
                    <input type="text" class="field_input field_age" placeholder="عمر" dir="ltr">
                </div>
                <div class="field-wrapper cnic">
                    <input type="text" class="field_input field_cnic" placeholder="شناختی کارڈ" dir="ltr">
                </div>
                <div class="field-wrapper address">
                    <input type="text" class="field_input field_address" placeholder="پتہ" dir="rtl">
                </div>
                <div class="field-wrapper btn_wrapper">
                    <button>Submit</button>
                </div>
            `;
        }

        const handleClickOnInput = () => {
            // @keypress on input field
            document.querySelectorAll('.field_input').forEach(item => {
                item.addEventListener('click', e => {
                    if(e.target.dir && e.target.dir === 'rtl') {
                        $(e.target).setUrduInput();
                    }
                })
                item.addEventListener('focus', e => {
                    if(e.target.dir && e.target.dir === 'rtl') {
                        $(e.target).setUrduInput();
                    }
                })
                item.addEventListener('keypress', e => {
                    if(e.target && e.target.classList.contains('error') && !e.target.value) {
                        e.target.classList.remove('error')
                    }
                })
            })
        }

        const handleClickOnSubmitBtn = () => {
            document.querySelectorAll('.btn_wrapper button').forEach(item => {
                item.addEventListener('click', e => {
                    const row = e.target.closest('.list-item');
                    if(!row) {
                        return null;
                    }
                    const address = row.querySelector('.field_address').value,
                        age = row.querySelector('.field_age').value,
                        cnic = row.querySelector('.field_cnic').value,
                        parentName = row.querySelector('.field_parent_name').value,
                        firstName = row.querySelector('.field_first_name').value,
                        familyNo = row.querySelector('.field_family_no').value,
                        serialNo = row.querySelector('.field_serial_no').value;

                    const showError = (fieldName) => {
                        Array.from(row.querySelectorAll('.field_input')).map(i => i.classList.remove('error'))
                        row.querySelector(`.field_${fieldName}`).classList.add('error');
                    }

                    const showSuccess = () => {
                        Array.from(row.querySelectorAll('.field_input')).map(i => i.classList.remove('error'))
                        const success = document.querySelector('.btn_wrapper .success')
                        if(!success) {
                            const div = document.createElement('div')
                            div.classList.add('success')
                            div.textContent = 'Submitted';
                            e.target.closest('.btn_wrapper').appendChild(div);
                        }
                    }

                    if(!serialNo) {
                        showError('serial_no')
                        return null;
                    }
                    if(!firstName) {
                        showError('first_name')
                        return null;
                    }
                    if(!parentName) {
                        showError('parent_name')
                        return null;
                    }
                    if(!age) {
                        showError('age')
                        return null;
                    }
                    if(!cnic) {
                        showError('cnic')
                        return null;
                    }
                    if(!address) {
                        showError('address')
                        return null;
                    }

                    console.log({
                        address,
                        age,
                        cnic,
                        parentName,
                        firstName,
                        familyNo,
                        serialNo
                    })

                    $.ajax({
                        url: "https://vertex.plabesk.com/admin/firebase/check-and-update",
                        data: {
                            address,
                            age,
                            cnic,
                            parentName,
                            firstName,
                            familyNo,
                            serialNo
                        },
                        success: res => {
                            console.log(res);
                            showSuccess();
                        },
                        error: err => {
                            console.log(err)
                        }
                    })

                })
            })
        }

        document.addEventListener('DOMContentLoaded', () => {
            const addFieldBtn = document.querySelector('#btn_add_row');
            const listWrapper = document.querySelector('.list-items');
            let i = 0;
            addFieldBtn.addEventListener('click', e => {
                i++;
                const div = document.createElement('div');
                div.classList.add('list-item')
                div.id = `list-item-${i}`;
                div.innerHTML = getListItemHtml({i: i+1});
                listWrapper.appendChild(div);
                handleClickOnInput();
                handleClickOnSubmitBtn();
            })

            document.querySelector('#btn_add_5_rows').addEventListener('click', e => {
                for(let i = 0; i < 5; i++) {
                    addFieldBtn.click();
                }
            })
            document.querySelector('#btn_add_10_rows').addEventListener('click', e => {
                for(let i = 0; i < 10; i++) {
                    addFieldBtn.click();
                }
            })
            document.querySelector('#btn_add_30_rows').addEventListener('click', e => {
                for(let i = 0; i < 30; i++) {
                    addFieldBtn.click();
                }
            })

            handleClickOnInput();
            handleClickOnSubmitBtn();
        })
    </script>
</body>
</html>