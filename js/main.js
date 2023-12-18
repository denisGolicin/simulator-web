const exitButton = document.querySelector('#exitButton');
const backButton = document.querySelector('#backButton');
const addClass = document.querySelector('#addClass');
const addSubject = document.querySelector('#addSubject');
const addDialog = document.querySelector('#addDialog');
const pageNext = document.querySelector('#pageNext');

const inputSubjects = document.getElementById('inputSubjects'); 
const addSubjectsRequest = document.querySelector('#addSubjectsRequest');

const addClassesList = document.getElementById('addClassesList'); 
const inputClasses = document.getElementById('inputClasses'); 
const addClassesRequest = document.querySelector('#addClassesRequest');
const classesTitle = document.querySelector('.class-name-title');

const templateClassesClose = document.querySelector('.template-classes-close');
const templateSubjectsClose = document.querySelector('.template-subjects-close');

const templateClasses = document.querySelector('.template-add-classes');
const templateSubjects = document.querySelector('.template-add-subjects');
const templateDialogs = document.querySelector('.template-dialog-edit');
const fileInput = document.querySelector(".file-input");

let isEditSubjects = false;
let isEditClasses = false;
let imageid = null;


const title = document.querySelector('#title');
const windowModes = document.querySelector('.window-modes');
const windowSelected = document.querySelector('.window-selected');
const windowSubjectsList = document.querySelector('.window-subjects-list');
const windowClassesList = document.querySelector('.window-classes-list');
const windowDialogsList = document.querySelector('.window-dialogs-list');
const windowStatsList = document.querySelector('.window-stats-list');

const selectTeaching = document.querySelector('.select-teaching');
const selectTesting = document.querySelector('.select-testing');
const selectDialogs = document.querySelector('.select-statistics');

let pageBack = null;
let pageActual = null;
let pageMode = '';
let pageSubjects = '';
let pageClasses = '';
let pageAnswerCorrect = 0;
openPage(windowModes);

selectTeaching.addEventListener('click', () => openPage(windowSubjectsList, 'teaching'));
selectTesting.addEventListener('click', () => openPage(windowSubjectsList, 'testing'));
selectDialogs.addEventListener('click', () => openPage(windowStatsList));

// selectDialogs.addEventListener('click', () => openPage(windowSubjectsList));

backButton.addEventListener('click', () => openPageBack());

exitButton.addEventListener('click', function(){
    deleteCookie("AUTH_TOKEN");
    window.location.reload();
});

function openPage(pageid, mode = '') {
    if(!pageid) return;
    pageid.style.display = 'flex';
    pageMode = (mode) ? (mode) : (pageMode);
    pageActual = pageid;

    let string = "";

    if(pageMode == 'teaching') string = "Обучение";
    if(pageMode == 'testing') string = "Тестирование";
    if(pageMode == 'statistics') string = "Статистика";
    
    if(pageid == windowModes) { 
        pageBack = null; title.innerHTML = `Выбор режима`; 
        exitButton.style.display = 'inline-block';
        addClass.style.display = 'none';
        addSubject.style.display = 'none';
        backButton.style.display = 'none';
        addDialog.style.display = 'none';
    }
    if(pageid == windowSubjectsList) { 
        pageBack = windowModes; title.innerHTML = `${string}. Предметы`;
        exitButton.style.display = 'none';
        addClass.style.display = 'none';
        addSubject.style.display = 'inline-block';
        backButton.style.display = 'inline-block'; 
        addDialog.style.display = 'none';

        createList(pageMode, "subjects");
    }
    if(pageid == windowClassesList) { 
        pageBack = windowSubjectsList; title.innerHTML = `${string}. Классы по предмету ${pageSubjects}`; 
        exitButton.style.display = 'none';
        addClass.style.display = 'inline-block';
        addSubject.style.display = 'none';
        backButton.style.display = 'inline-block';
        addDialog.style.display = 'none';

        createList(pageMode, "classes");
    }

    if(pageid == windowDialogsList){

        pageBack = windowClassesList; title.innerHTML = `${string}. Диалоги по предмету ${pageSubjects} за ${pageClasses} класс`; 
        exitButton.style.display = 'none';
        addClass.style.display = 'none';
        addSubject.style.display = 'none';
        backButton.style.display = 'inline-block';
        addDialog.style.display = 'inline-block';

        showDialog(pageMode);

        console.log('111')

    }

    if(pageid == windowStatsList){
        pageBack = windowModes; title.innerHTML = `Статистика`; 
        exitButton.style.display = 'none';
        addClass.style.display = 'none';
        addSubject.style.display = 'none';
        backButton.style.display = 'inline-block';
        addDialog.style.display = 'none';
        pageNext.style.display = 'inline-block';

        showLoader();
        getListStats(1)
        .then(json => {
            createStatsList(json);
            hideLoader();
        });
    }

    if(pageBack == null || !pageBack) return;
    pageBack.style.display = 'none';
}

function openPageBack() {

    if(pageActual == null || !pageActual) return;
    if(pageBack == null || !pageBack) return;

    pageBack.style.display = 'flex';
    pageActual.style.display = 'none';

    pageActual = pageBack;

    let string = "";
    if(pageMode == 'teaching') string = "Обучение";
    if(pageMode == 'testing') string = "Тестирование";
    if(pageMode == 'statistics') string = "Статистика";

    if(pageBack == windowModes) { 
        pageBack = null; title.innerHTML = `Выбор режима`; 
        exitButton.style.display = 'inline-block';
        addClass.style.display = 'none';
        addSubject.style.display = 'none';
        backButton.style.display = 'none';
        addDialog.style.display = 'none';
        pageNext.style.display = 'none';
    }

    if(pageBack == windowSubjectsList) { 

        title.innerHTML = `${string}. Предметы`; 
        pageBack = windowModes; 
        exitButton.style.display = 'none';
        addClass.style.display = 'none';
        addSubject.style.display = 'inline-block';
        backButton.style.display = 'inline-block';
        addDialog.style.display = 'none';
        pageSubjects = '';
        createList(pageMode, "subjects");
    }
    if(pageBack == windowClassesList)
    {
        title.innerHTML = `${string}. Классы по предмету ${pageSubjects}`; 
        pageBack = windowSubjectsList; 
        exitButton.style.display = 'none';
        addClass.style.display = 'inline-block';
        addSubject.style.display = 'none';
        backButton.style.display = 'inline-block';
        addDialog.style.display = 'none';
        pageClasses = '';
        createList(pageMode, "classes");
    }

}

function deleteCookie(cookieName) {
    document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function createList(mode, type) {
    showLoader();

    getList(mode, type)
    .then(json => {
        console.log(json);
        hideLoader();

        showList(type, json, mode);
    })
    .catch(error => { alert(error.message); hideLoader(); });

}
function createStatsList(json){

    windowStatsList.innerHTML = "";

    if(!json.hasOwnProperty('items')){
        windowStatsList.insertAdjacentHTML('beforeend', 
            `
                <div class="select-button" style="display: flex; justify-content:center;">Нет данных о статистике!</div>
        
            `
        );

        return;
    }

    for(let i = 0; i < json.items.length; i++){

        let color = '';
       if(json.items[i].precent < 30) color = 'brown';
       if(json.items[i].precent > 30 && json.items[i].precent < 70) color = '#5f5e00';
       if(json.items[i].precent > 70) color = 'green';
        

        windowStatsList.insertAdjacentHTML('beforeend', 
            `
                <div class="select-button">
                    <div class="stats-wrapper">
                        <div class="stats-info-block">
                            <div class="stats-name">
                                <div>${json.items[i].name}</div>
                                <div class="stats-curcle" style = "background-color: ${color}">${json.items[i].precent}%</div>
                            </div>
                            <div class="stats-type">
                                <p><span id="statsSubjects">${json.items[i].subjects}</span> за <span id="statsClasses">${json.items[i].classes} класс</span></p>
                                <p class="stats-result">Ответил правильно на <span id="statsAnswers">${json.items[i].answers_correct}</span> из <span id="statsDialogs">${json.items[i].dialogs}</span></p>
                                <p>Время: <span id="statsDate">${formatDateToKaliningradTime(json.items[i].date)}</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
        
            `
        );
    }
}

async function getList(mode, type) {
    const response = await fetch(`${domain}/api/v1/${type}/${mode}Read.php`);
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

function getUniqueClassesForSubjects(subjects) {
    return result.dialogs
        .filter(dialog => dialog.subjects === subjects)
        .map(dialog => dialog.class)
        .filter((value, index, self) => self.indexOf(value) === index);
}

function showList(type, json, mode){
    let wrapper = document.querySelector(`.window-${type}-list`);
    const property = `${mode}_${type}`;
    const name = `${type}_name`;

    console.log(json[property]);
    wrapper.innerHTML = '';

    if(type === 'classes'){

        dialogsForFilter(mode)
        .then(result => { 
            const allClasses = result.dialogs.filter(dialog => dialog.subjects === pageSubjects)
                .map(dialog => dialog.classes);
            const uniqueClasses = [...new Set(allClasses)];
            console.log(uniqueClasses)

            if(uniqueClasses.length <= 0 || result.hasOwnProperty("message") == 'Диалоги для обучения не найдены.') {
                wrapper.insertAdjacentHTML('afterbegin', 
                    `<div class="select-button" style = "color: brown;">Пустой список. Cоздайте новый элемент</div>`
                );
                return;
            }showList

            for (let i = 0; i < uniqueClasses.length; i++)
            {
                wrapper.insertAdjacentHTML('afterbegin', 
                    `<div class="select-button" data-${type}="${uniqueClasses[i]}" data-${mode}="${mode}" data-${type}="${type}">
                        ${uniqueClasses[i]} 
                        <div class="buttons-editor">
                            <div class="edit-button edit-${mode}-${type}" data-${type}="${uniqueClasses[i]}">
                                <img src="src/web/icons/edit.svg">
                            </div>
                            <div class="delete-button delete-${mode}-${type}" data-${type}="${uniqueClasses[i]}">
                            <img src="src/web/icons/delete.svg">
                            </div>
                        </div>
                    </div>`
                );
            }
            addEvents(wrapper, type, mode);
         })
        .catch(error => { 
            wrapper.insertAdjacentHTML('afterbegin', 
                `<div class="select-button" style = "color: brown;">Пустой список. Cоздайте новый элемент</div>`
            );
         });
    } else {
        if (json.hasOwnProperty("message")){

            wrapper.insertAdjacentHTML('afterbegin', 
                `<div class="select-button" style = "color: brown;">Пустой список. Cоздайте новый элемент</div>`
            );
            return;
        }

        for (let i = 0; i < json[property].length; i++) {
            console.log(json[property][i][name]);
            wrapper.insertAdjacentHTML('afterbegin', 
                `<div class="select-button" data-${type}="${json[property][i][name]}" data-${mode}="${mode}" data-${type}="${type}">
                    ${json[property][i][name]} 
                    <div class="buttons-editor">
                        <div class="edit-button edit-${mode}-${type}" data-${type}="${json[property][i][name]}">
                            <img src="src/web/icons/edit.svg">
                        </div>
                        <div class="delete-button delete-${mode}-${type}" data-${type}="${json[property][i][name]}">
                        <img src="src/web/icons/delete.svg">
                        </div>
                    </div>
                </div>`
            );
        }
        addEvents(wrapper, type, mode);
    }
}
async function dialogsForFilter(mode, filter = null) {
    showLoader();

    try {
        const json = await getDialogs(mode, filter);
        hideLoader();
        console.log(json);
        return json;
    } catch (error) {
        alert(error.message); hideLoader();
    }

}

async function getDialogs(mode, filter = null) {
    let response = '';

    if(filter) response = await fetch(`${domain}/api/v1/${mode}/read.php?subjects=${pageSubjects}&classes=${pageClasses}`);
    else response = await fetch(`${domain}/api/v1/${mode}/read.php`);

    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

function addEvents(wrapper, type, mode) {

    let selectButton = wrapper.querySelectorAll('.select-button');
    for(let i = 0; i < selectButton.length; i++){
        selectButton[i].addEventListener('click', function(event){

            if (event.target.closest('.delete-button')) {
                console.log(event.target.closest('.delete-button'))
                
                console.log(event.target.closest('.delete-button').dataset[type]);

                let success;
                if(type == 'subjects') {
                    success = confirm(`Это действие удалит все классы связаные с предметом ${event.target.closest('.delete-button').dataset[type]}, а также все диалоги!\nВы уверены что хотите удалить весь предмет?`);
                } else {
                    success = confirm(`Это действие удалит все диалоги связаные с предметом ${pageSubjects} за ${event.target.closest('.delete-button').dataset[type]} класс!\nВы уверены что хотите удалить весь класс?`);
                }

                if(!success) return;
                showLoader();
                deleteItemForList(pageMode, type, event.target.closest('.delete-button').dataset[type])
                .then(json => {
                    hideLoader();
                    createList(pageMode, type);
                })
                .catch(error => { alert(error.message); hideLoader(); });

                return;
            }
            if (event.target.closest('.edit-button')) {
                console.log(event.target.closest('.edit-button'))
                if(type == 'subjects'){
                    templateSubjects.style.display = 'flex';
                    isEditSubjects = true;
                    addSubjectsRequest.innerHTML = "Редактировать";
                    inputSubjects.placeholder = event.target.closest('.edit-button').dataset[type];
                } else {
                    templateClasses.style.display = 'flex';
                    isEditClasses = true;
                    addClassesList.style.display = 'none';
                    addClassesRequest.innerHTML = "Редактировать";
                    classesTitle.innerHTML = 'Редактирование класса';
                    inputClasses.placeholder = event.target.closest('.edit-button').dataset[type];
                }
                return;
            }

            if(pageSubjects == ''){
                pageSubjects = selectButton[i].getAttribute(`data-${type}`);
                wrapper.style.display = 'none';
                openPage(windowClassesList);
                return;
            }

            pageClasses = selectButton[i].getAttribute(`data-${type}`);
            console.log(pageSubjects + " - " + pageClasses);

            //dialogsForFilter(mode, true);
            openPage(windowDialogsList);
        })
    }
}

function addEventsDialog(wrapper, status) {

    let editDialog = wrapper.querySelectorAll('.buttons-editor');
    for(let i = 0; i < editDialog.length; i++){
        editDialog[i].addEventListener('click', function(event){

            if (event.target.closest('.delete-dialog')) {
                console.log(event.target.closest('.delete-dialog').dataset.dialogid);
                deleteDialogForID(pageMode, event.target.closest('.delete-dialog').dataset.dialogid)
                .then (json => { 
                    if(json.message){
                        showDialog(pageMode);
                    }
                })
                return;
            }
            if (event.target.closest('.edit-dialog')) {
                console.log(event.target.closest('.edit-dialog'));
                
                showDialogOne(pageMode, event.target.closest('.edit-dialog').dataset.dialogid)
                .then(json =>{
                    console.log(json);
                    openEditDialog(json);
                })
                return;
            }

            if (event.target.closest('.edit-dialog-audio')) {

                console.log(event.target.closest('.edit-dialog-audio').dataset.dialogid);

                showLoader();
                synthesis(pageMode, event.target.closest('.edit-dialog-audio').dataset.dialogid, event.target.closest('.edit-dialog-audio').dataset.status)
                .then(json => {
                    showDialog(pageMode);
                    hideLoader();
                })
            }

        });
    }

    let editDialogImg = wrapper.querySelectorAll('.edit-dialog-img');

    for(let i = 0; i < editDialogImg.length; i++){
        editDialogImg[i].addEventListener('click', function(){
            imageid = editDialogImg[i].dataset.dialogid;
            console.log(editDialogImg[i].dataset.dialogid);
            fileInput.click();
        });
    }
}

fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];

    if (file) {
        if (file.type === "image/png" || file.type === "image/jpeg") {
            const formData = new FormData();
            formData.append('id', imageid);
            formData.append('file', file);
            showLoader();
            fetch(`${domain}/api/v1/${pageMode}/upload.php`, {
                method: 'POST',
                body: formData,
            })
            .then((response) => response.json()) 
            .then((data) => {
                console.log(data);
                showDialog(pageMode);
                hideLoader();
            })
        } else 

        alert('Формат изображения: png, jpg');
    }

});


async function synthesis(mode, id, status) {

    let url = '';
    let data = {
        id: id,
        mode: mode
    };

    if(status == 'generated'){
        url = `${domain}/api/v1/synthesis/check.php`;
    } else {
        url = `${domain}/api/v1/synthesis/add.php`;
    }   


    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify(data) 
        });

        if (!response.ok) {
            throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        throw new Error(`Произошла ошибка при выполнении POST-запроса: ${error.message}`);
    }
}

function showDialog(mode) {

    dialogsForFilter(mode, true)
    .then(result => { 
        let wrapper = document.querySelector(".window-dialogs-list");
        wrapper.innerHTML = '';
        if (result.hasOwnProperty("message")){

            wrapper.insertAdjacentHTML('afterbegin', 
                `<div class="select-button" style = "color: brown; margin-bottom: 20px;">Пустой список. Cоздайте новый элемент</div>`
            );
            return;
        }
        for (let i = 0; i < result.dialogs.length; i++)
        {   
            if(result.dialogs[i].question == null || result.dialogs[i].question == '') result.dialogs[i].question = 'Нет данных';
            if(result.dialogs[i].answer_0 == null || result.dialogs[i].answer_0 == '') result.dialogs[i].answer_0 = 'Нет данных';
            if(result.dialogs[i].answer_1 == null || result.dialogs[i].answer_1 == '') result.dialogs[i].answer_1 = 'Нет данных';
            if(result.dialogs[i].answer_2 == null || result.dialogs[i].answer_2 == '') result.dialogs[i].answer_2 = 'Нет данных';
            if(result.dialogs[i].answer_3 == null || result.dialogs[i].answer_3 == '') result.dialogs[i].answer_3 = 'Нет данных';

            createDialog(
                wrapper,
                result.dialogs[i].id, 
                result.dialogs[i].question, 
                result.dialogs[i].answer_correct,
                result.dialogs[i].answer_0, 
                result.dialogs[i].answer_1, 
                result.dialogs[i].answer_2, 
                result.dialogs[i].answer_3, 
                result.dialogs[i].audio_url, 
                result.dialogs[i].image_url,
                result.dialogs[i].modified,
                result.dialogs[i].status
            );
        }

        addEventsDialog(wrapper);
    })
}

function createDialog(wrapper, id, question, answer_correct, answer_0, answer_1, answer_2, answer_3, audio_url, image_url, edited, status){

    let answers_str = `
        <div class="dialog-answer"><span class="span-dialog">Ответ<br></span>${answer_0}</div>
    `;

    if(status == 'none') audio_url = 'default.wav';
    if(status == 'generated') audio_url = 'generated.mp3';

    let audio_check = `
        <div class="dialog-audio">
            <audio src="${domain}/src/audio/${audio_url}" controls></audio>
            <div class="buttons-editor">
                <div class="edit-button edit-dialog-audio" data-dialogid="${id}" data-status="${status}">
                    <img src="src/web/icons/update.svg">
                </div>
            </div>
        </div>
    `;

    if(pageMode == "testing"){
        answers_str = 
        `
            <div class="dialog-answer"><span class="span-dialog">Ответ<br></span>${answer_0}</div>
            <div class="dialog-answer"><span class="span-dialog">Ответ<br></span>${answer_1}</div>
            <div class="dialog-answer"><span class="span-dialog">Ответ<br></span>${answer_2}</div>
            <div class="dialog-answer"><span class="span-dialog">Ответ<br></span>${answer_3}</div>
        
        `
        audio_check = '';
    }

    wrapper.insertAdjacentHTML('afterbegin', 
        `
        <div class="dialog-item">
            <div class="dialog-header">
                Диалог №${id}
                <div class="buttons-editor">
                    <div class="edit-button edit-dialog" data-dialogid="${id}">
                        <img src="src/web/icons/edit.svg">
                    </div>
                    <div class="delete-button delete-dialog" data-dialogid="${id}">
                        <img src="src/web/icons/delete.svg">
                    </div>
                </div>
            </div>
            <div class="dialog-qustiong"><span class="span-dialog">Вопрос<br></span>${question}</div>
            ${answers_str}
            ${audio_check}
            <div class="dialog-image edit-dialog-img" data-dialogid="${id}">
                <img src="${domain}/src/image/${image_url}" alt="">
            </div>
            <div class="dialog-modified">Последнее редактирование: ${formatDateToKaliningradTime(edited)}</div>
        </div>`
    );
    if(pageMode == "testing"){

        const answersItem = document.querySelectorAll('.dialog-answer');
        for(let i = 0; i < answersItem.length; i++){
            if(i == answer_correct){
                answersItem[i].style.background = 'green';
            }
        }
    }
}

async function addDialogForFilter(mode, type, classes) {

    showLoader();
    let wrapper = document.querySelector(".window-dialogs-list");
    try {
        const json = await addDialogsRequest(mode, type, classes);
        hideLoader();
        console.log(json);
        return json;
    } catch (error) {
        alert(error.message); hideLoader();
    }

}

async function addDialogsRequest(mode, type, classes) {

    let response = '';
    response = await fetch(`${domain}/api/v1/${mode}/add.php?subjects=${type}&classes=${classes}`);

    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

async function deleteItemForList(mode, type, name) {

    let response = '';
    if(type == 'subjects'){
        response = await fetch(`${domain}/api/v1/${type}/${mode}Delete.php?subjects_name=${name}`);
    } else {
        response = await fetch(`${domain}/api/v1/${type}/${mode}Delete.php?subjects_name=${pageSubjects}&classes_name=${name}`);
    }

    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

addDialog.addEventListener('click', () => {
    let wrapper = document.querySelector(".window-dialogs-list");
    addDialogForFilter(pageMode, pageSubjects, pageClasses)
    .then (json => { 
        if(json.message){
            showDialog(pageMode, json);
        }
    })
});

async function deleteDialogForID(mode, id) {
    showLoader();
    try {
        const json = await deleteDialogsRequest(mode, id);
        hideLoader();
        console.log(json);
        return json;
    } catch (error) {
        alert(error.message); hideLoader();
    }

}

async function deleteDialogsRequest(mode, id) {

    let response = '';
    response = await fetch(`${domain}/api/v1/${mode}/delete.php?id=${id}`);

    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

function openEditDialog(json){
    if(pageMode == 'teaching'){
        if(json.question == null || json.question == '') json.question = 'Нет данных';
        if(json.answer_0 == null || json.answer_0 == '') json.answer_0 = 'Нет данных';
        if(json.answer_1 == null || json.answer_1 == '') json.answer_1 = 'Нет данных';
        if(json.answer_2 == null || json.answer_2 == '') json.answer_2 = 'Нет данных';
        if(json.answer_3 == null || json.answer_3 == '') json.answer_3 = 'Нет данных';
        templateDialogs.style.display = 'flex';
        templateDialogs.innerHTML = '';
        templateDialogs.insertAdjacentHTML('afterbegin', 
            `
            <div class="template-wrapper">
                <p class="dialog-edit-title">Редактирование диалога №${json.id}</p>
                <div class="input-dialog">

                    <p class="question-dialog-edit">Вопрос</p>
                    <textarea name="" id="dialogQuestion" cols="10" rows="3">${json.question}</textarea>

                    <div class="input-dialog-checkbox">
                        <p class="answer-dialog-edit">Ответ</p>
                    </div>
                    <textarea name="" id="dialogAnswer_0" cols="10" rows="3">${json.answer_0}</textarea>
                </div>

                <button id="editDialogRequest">Редактировать</button>
                <button class="template-dialog-close">Закрыть</button>
            </div>`
        );

        const editDialogRequest = document.querySelector('#editDialogRequest');
        editDialogRequest.addEventListener('click', function(){
            const dialogQuestion = document.querySelector('#dialogQuestion');
            const dialogAnswer_0 = document.querySelector('#dialogAnswer_0');
            editDialogsForID(pageMode, json.id, dialogQuestion.value, json.answer_correct, dialogAnswer_0.value, json.answer_1, json.answer_2, json.answer_3)
            .then(result =>{ 

                templateDialogs.style.display = 'none';
                showDialog(pageMode);
            });

        });

    } else {

        if(json.question == null || json.question == '') json.question = 'Нет данных';
        if(json.answer_0 == null || json.answer_0 == '') json.answer_0 = 'Нет данных';
        if(json.answer_1 == null || json.answer_1 == '') json.answer_1 = 'Нет данных';
        if(json.answer_2 == null || json.answer_2 == '') json.answer_2 = 'Нет данных';
        if(json.answer_3 == null || json.answer_3 == '') json.answer_3 = 'Нет данных';
        templateDialogs.style.display = 'flex';
        templateDialogs.innerHTML = '';
        templateDialogs.insertAdjacentHTML('afterbegin', 
            `
            <div class="template-wrapper">
                <p class="dialog-edit-title">Редактирование диалога №${json.id}</p>
                <div class="input-dialog">

                    <p class="question-dialog-edit">Вопрос</p>
                    <textarea name="" id="dialogQuestion" cols="10" rows="3">${json.question}</textarea>

                    <div class="input-dialog-checkbox">
                        <p class="answer-dialog-edit">Ответ</p>
                        <input type="checkbox">
                    </div>
                    <textarea name="" id="dialogAnswer_0" cols="10" rows="3">${json.answer_0}</textarea>

                    <div class="input-dialog-checkbox">
                        <p class="answer-dialog-edit">Ответ</p>
                        <input type="checkbox">
                    </div>
                    <textarea name="" id="dialogAnswer_1" cols="10" rows="3">${json.answer_1}</textarea>

                    <div class="input-dialog-checkbox">
                        <p class="answer-dialog-edit">Ответ</p>
                        <input type="checkbox">
                    </div>
                    <textarea name="" id="dialogAnswer_2" cols="10" rows="3">${json.answer_2}</textarea>

                    <div class="input-dialog-checkbox">
                        <p class="answer-dialog-edit">Ответ</p>
                        <input type="checkbox">
                    </div>
                    <textarea name="" id="dialogAnswer_3" cols="10" rows="3">${json.answer_3}</textarea>
                </div>

                <button id="editDialogRequest">Редактировать</button>
                <button class="template-dialog-close">Закрыть</button>
            </div>`
        );

        const checkboxes = document.querySelectorAll('.input-dialog-checkbox input');
        checkboxes[json.answer_correct].checked = true;
        pageAnswerCorrect = json.answer_correct;

        checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', () => {
                checkboxes.forEach((otherCheckbox, otherIndex) => {
                    if (otherIndex !== index) {
                        otherCheckbox.checked = false;
                        console.log(`${index}`);
                        pageAnswerCorrect = index;
                    }
                });
            });
        });        

        const editDialogRequest = document.querySelector('#editDialogRequest');
        editDialogRequest.addEventListener('click', function(){
            const dialogQuestion = document.querySelector('#dialogQuestion');
            const dialogAnswer_0 = document.querySelector('#dialogAnswer_0');
            const dialogAnswer_1 = document.querySelector('#dialogAnswer_1');
            const dialogAnswer_2 = document.querySelector('#dialogAnswer_2');
            const dialogAnswer_3 = document.querySelector('#dialogAnswer_3');
            editDialogsForID(pageMode, json.id, dialogQuestion.value, pageAnswerCorrect, dialogAnswer_0.value, dialogAnswer_1.value, dialogAnswer_2.value, dialogAnswer_3.value)
            .then(result =>{ 

                templateDialogs.style.display = 'none';
                showDialog(pageMode);
            });

        });
    }



    const templateDialogClose = document.querySelector('.template-dialog-close');
    templateDialogClose.addEventListener('click', function(){
        templateDialogs.style.display = 'none';
    });

}

async function editDialogsForID(mode, id, question, answer_correct, answer_0, answer_1, answer_2, answer_3) {
    let url = `${domain}/api/v1/${mode}/edit.php`;
    let data = {
        id: id,
        question: question,
        answer_correct: answer_correct,
        answer_0: answer_0,
        answer_1: answer_1,
        answer_2: answer_2,
        answer_3: answer_3
    };

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify(data) 
        });

        if (!response.ok) {
            throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        throw new Error(`Произошла ошибка при выполнении POST-запроса: ${error.message}`);
    }
}


addClass.addEventListener('click', function(){
    templateClasses.style.display = 'flex';
    isEditClasses = false;
    addClassesRequest.innerHTML = "Добавить";
    inputClasses.placeholder = 'Название класса';
    classesTitle.innerHTML = 'Выберите класс из списка или создайте новый';
    populateClassesList();
});
addSubject.addEventListener('click', function(){
    templateSubjects.style.display = 'flex';
});

templateClassesClose.addEventListener('click', function(){
    templateClasses.style.display = 'none';
    isEditClasses = false;
    addClassesRequest.innerHTML = "Добавить";
    inputClasses.placeholder = 'Название класса';
    addClassesList.style.display = 'flex';
    classesTitle.innerHTML = 'Выберите класс из списка или создайте новый';
});
templateSubjectsClose.addEventListener('click', function(){
    templateSubjects.style.display = 'none';
    isEditSubjects = false;
    addSubjectsRequest.innerHTML = "Добавить";
    inputSubjects.placeholder = 'Название предмета';
});

async function populateClassesList() {

    getList(pageMode, 'classes')
    .then(json => {
        addClassesList.innerHTML = ''; 
        console.log(json)

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Выберите класс';
        addClassesList.appendChild(defaultOption);

        if(pageMode == 'teaching'){
            for(let i = 0; i < json.teaching_classes.length; i++){
                const option = document.createElement('option');
                option.value = json.teaching_classes[i].classes_name; 
                option.text = json.teaching_classes[i].classes_name; 
                addClassesList.appendChild(option);
            }
        } else {
            for(let i = 0; i < json.testing_classes.length; i++){
                const option = document.createElement('option');
                option.value = json.testing_classes[i].classes_name; 
                option.text = json.testing_classes[i].classes_name; 
                addClassesList.appendChild(option);
            }
        }
        
    })
    .catch(error => { alert(error.message); hideLoader(); });

}

addClassesList.addEventListener('change', () => {
    inputClasses.value = addClassesList.value;
});

addClassesRequest.addEventListener('click', function(){

    if(inputClasses.value == ''){
        alert('Введите значения для поля класс!');
        return;
    }

    if(isEditClasses){
        showLoader();
        editClasses(pageMode, "classes", inputClasses.placeholder, inputClasses.value)
        .then(json => {
            console.log(json);
            createList(pageMode, "classes");
            templateClasses.style.display = 'none';
            inputClasses.value = '';
            isEditClasses = false;
            hideLoader();
        })
        .catch(error => { alert(error.message); hideLoader(); });
        return;
    }

    showLoader();
    addClassForFilter(pageMode, "classes", inputClasses.value)
    .then(json => {
        console.log(json);
        createList(pageMode, "classes");
        templateClasses.style.display = 'none';
        inputClasses.value = '';
        hideLoader();
    })
    .catch(error => { alert(error.message); hideLoader(); });
});

async function editClasses(mode, type, old_name, new_name){
    const response = await fetch(`${domain}/api/v1/${type}/${mode}Edit.php?subjects_name=${pageSubjects}&classes_name=${old_name}&classes_name_new=${new_name}`);
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

async function addClassForFilter(mode, type, classes) {
    const response = await fetch(`${domain}/api/v1/${type}/${mode}Add.php?subjects_name=${pageSubjects}&classes_name=${classes}`);
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

addSubjectsRequest.addEventListener('click', function(){

    if(inputSubjects.value == ''){
        alert('Введите предмет!');
        return;
    }
    console.log("asdsadsadasd" + inputSubjects.placeholder)
    showLoader();
    addSubjectsForFilter(pageMode, inputSubjects.placeholder, inputSubjects.value, isEditSubjects)
    .then(json => {
        console.log(json);
        createList(pageMode, "subjects");
        inputSubjects.value = '';
        templateSubjects.style.display = 'none';
        isEditSubjects = false;
        addSubjectsRequest.innerHTML = "Добавить";
        inputSubjects.placeholder = 'Название предмета';
        hideLoader();
    })
    .catch(error => { alert(error.message); hideLoader(); });
});

async function addSubjectsForFilter(mode, current_name, new_name, edit) {

    let = response = '';

    if(edit) {
        response = await fetch(`${domain}/api/v1/subjects/${mode}Edit.php?current_name=${current_name}&new_name=${new_name}`);
    } else {
        response = await fetch(`${domain}/api/v1/subjects/${mode}Add.php?subjects_name=${new_name}`);
    }
    
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

async function showDialogOne(mode, id) {
    const response = await fetch(`${domain}/api/v1/${mode}/read.php?id=${id}`);
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}

async function getListStats(page) {
    const response = await fetch(`${domain}/api/v1/statistics/read.php?page=${page}`);
    if (!response.ok) {
        throw new Error(`Произошла ошибка при запросе. Статус: ${response.status}, Подробности: ${response.statusText}`);
    }
    return await response.json();
}





function formatDateToKaliningradTime(dateString) {
    const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Europe/Kaliningrad',
    };

    const date = new Date(dateString);
    date.setHours(date.getHours() - 1); 
    const formattedDate = date.toLocaleString('ru-RU', options);
    return formattedDate;
}

const checkboxes = document.querySelectorAll('.input-dialog-checkbox input');
const textareas = document.querySelectorAll('.input-dialog-checkbox textarea');

checkboxes.forEach((checkbox, index) => {
    checkbox.addEventListener('change', () => {
        checkboxes.forEach((otherCheckbox, otherIndex) => {
            if (otherIndex !== index) {
                otherCheckbox.checked = false;
                console.log(`${index}`);
                pageAnswerCorrect = index;
            }
        });
    });
});












