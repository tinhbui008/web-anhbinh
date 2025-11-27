

  const questions = window.questions;
  
  let timerInterval = null;
  let currentQuestion = parseInt(localStorage.getItem('currentQuestion')) || 0;
  let score = parseInt(localStorage.getItem('score')) || 0;
  let startTime = parseInt(localStorage.getItem('startTime')) || 0;

  let totalTime = window.totalTime;
  let totalSeconds = window.totalTime;
  let idPost = window.idPost;
  let idCategory = window.idCategory;

  let answers = JSON.parse(localStorage.getItem('answers')) || [];
  let questionType = '';
  let result_test = [];
 

  //let gameStarted = !!startTime;

  //console.log(questions);
  
  function getTimeLeft() {
    const now = Date.now();
    return Math.max(0, totalTime - Math.floor((now - startTime) / 1000));
  }
  
  function formatTime(seconds) {
    const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
    const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    return `${m}:${s}`;
  }
  
  function updateTimer() {
    //if (!gameStarted) return;

    const timeLeft = getTimeLeft();
    document.getElementById('timer').textContent = formatTime(timeLeft);
    if (timeLeft <= 0) {
      showScore();
    }
  }
  timerInterval = setInterval(updateTimer, 1000);
  
  function loadQuestion() {
    // ### Xóa active nút next
    jQuery('.quiz-next').removeClass('quiz-next-active');

    // ### display button - back return
    if(currentQuestion==0){
      jQuery('.quiz-back-btn').addClass('quiz-back-hidden');
    }else{
      jQuery('.quiz-back-btn').removeClass('quiz-back-hidden');
    }

    jQuery('#answerText').hide();


    // ### calculate pregress bar
    const progress = ((currentQuestion+1) * 100) / questions.length;
    jQuery('#questionProgress-active').css('width',progress+'%');
    jQuery('#question-current').text((currentQuestion+1));
    jQuery('#question-length').text(questions.length);


    // ### check question type
    if (currentQuestion >= questions.length) return showScore();
    const q = questions[currentQuestion];
    document.getElementById('questionText').textContent = q.question;

    const container = document.getElementById('answerButtons');
    container.innerHTML = '';

    document.getElementById('result').textContent = '';

    questionType = q.question_display_type;


    // Kiểm tra loại câu hỏi: có hình, video hay audio
    if(q.question_display_type == 'cauhoi_image'){
      jQuery('#answerButtons').html(jQuery('.quiz-box-2_1').clone());

    }else if(q.question_display_type == 'cauhoi_noimage'){
      jQuery('#answerButtons').html(jQuery('.quiz-box-2_1').clone().addClass('quiz-box-noimage'));

    }else if(q.question_display_type == 'cauhoi_audio'){
      jQuery('#answerButtons').html(jQuery('.quiz-box-2_1').clone().addClass('quiz-box-noimage').addClass('quiz-box-audio'));

    }else if(q.question_display_type == 'cauhoi_video'){
      jQuery('#answerButtons').html(jQuery('.quiz-box-2_1').clone());
      jQuery('#answerButtons .quiz-box-2_1 .quiz-box-right').html(jQuery('.quiz-box-2_3 video').clone());

    }else if(q.question_display_type == 'cauhoi_tuluan'){
      jQuery('#answerText').show();
    }

  
    // Kiểm tra kiểu đáp án : 1 đáp án - nhiều đáp án - tự luận
    jQuery('.quiz-box-2_1').find('.quiz-box-left').empty(); // Xóa trống div

    if (q.type === 'choice') { // ### : 1 đáp án
      for (const key in q.options) {
        const label = document.createElement('label');
        label.innerHTML = `<input type="radio" name="multi" value="${key}"> <span>${key}. ${q.options[key]}</span>`;
        label.style.display = 'flex';
        label.classList.add("answer-label", "answer-label-"+key);
        //container.appendChild(label);
        jQuery('.quiz-box-2_1').find('.quiz-box-left').append(label);

        /*const btn = document.createElement('button');
        btn.textContent = `${key}. ${q.options[key]}`;
        btn.className = 'answer-btn';
        btn.onclick = () => handleAnswer(key);
        container.appendChild(btn);*/
      }
    } else if (q.type === 'multi-choice') { // ### : nhiều đáp án
      for (const key in q.options) {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" name="multi" value="${key}"> <span>${key}. ${q.options[key]}</span>`;
        label.style.display = 'flex';
        label.classList.add("answer-label", "answer-label-"+key);
        //container.appendChild(label);
        jQuery('.quiz-box-2_1').find('.quiz-box-left').append(label);
      }

      // Nút submit câu trả lời
      /*const btn = document.createElement('button');
      btn.textContent = 'Trả lời';
      btn.onclick = () => {
        const selected = [...document.querySelectorAll('input[name="multi"]:checked')].map(i => i.value);
        handleAnswer(selected);
      };
      container.appendChild(btn);*/

    } else if (q.type === 'text') { // ### : tự luận
      const input = document.createElement('textarea');
      input.type = 'text';
      input.id = 'textAnswer';
      input.value = answers[currentQuestion]?.answer || '';
      container.appendChild(input);

      /*const btn = document.createElement('button');
      btn.id = 'submitText';
      btn.textContent = 'Trả lời';
      btn.onclick = () => handleAnswer(input.value.trim().toLowerCase());
      container.appendChild(btn);*/
    }


    // ### hứng sự kiện của các input sau khi được tạo cho câu hỏi: kiểm tra active nút next
    if (q.type === 'choice' || q.type === 'multi-choice') {
      // Lấy tất cả các radio button có name là 'multi'
      const inputs_tmp = document.getElementsByName('multi');

      // Thêm sự kiện 'change' cho tất cả radio button
      inputs_tmp.forEach(function(input) {
          input.addEventListener('change', function() {
              if (input.checked) {
                  jQuery('.quiz-next').addClass('quiz-next-active');
              }
          });
      });
    }else if(q.type === 'text'){
      const textarea = document.getElementById('textAnswer');
      // Thêm event listener cho sự kiện 'change'
      textarea.addEventListener('focus', function() {
          jQuery('.quiz-next').addClass('quiz-next-active');
      });
    }


    // ### Nếu câu hỏi có hình ảnh --> append hình ảnh
    jQuery('#question-audio').empty();
    if(q.question_display_type == 'cauhoi_image'){

      // ### Thêm hình
      if(q.image!='') {
        jQuery('.quiz-box-2_1').find('.quiz-box-right img').attr('src',q.image);
      }else{
        jQuery('.quiz-box-2_1').find('.quiz-box-right').empty(); // Xóa trống div
        jQuery('.quiz-box-2_1').find('.quiz-box-right').append('<span>No image</span>');
      }

    }else if(q.question_display_type == 'cauhoi_audio'){
      jQuery('.quiz-box-2_2 audio source').attr('src',q.audio);
      jQuery('#question-audio').html(jQuery('.quiz-box-2_2 audio').clone());

    }else if(q.question_display_type == 'cauhoi_video'){

      // ### Thêm video
      if(q.video!='') {
        jQuery('.quiz-box-2_1').find('.quiz-box-right video source').attr('src',q.video);
      }else{
        jQuery('.quiz-box-2_1').find('.quiz-box-right').empty(); // Xóa trống div
        jQuery('.quiz-box-2_1').find('.quiz-box-right').append('<span>No Video</span>');
      }
    }

  
    if (currentQuestion > 0) {
      /*const back = document.createElement('button');
      back.textContent = '⬅️ Quay lại';
      back.className = 'back-btn';
      back.onclick = () => {
        currentQuestion--;
        loadQuestion();
      };
      container.appendChild(back);*/
    }
  
    /*const skip = document.createElement('button');
    skip.textContent = '⏭️ Bỏ qua';
    skip.className = 'skip-btn';
    skip.onclick = () => handleAnswer(null, true);
    container.appendChild(skip);*/
  }


  // ### nút : quay lại câu trước đó
  jQuery('.quiz-back-btn').click(function(){
    currentQuestion--;
    loadQuestion();
  });

  // ### nút : bỏ qua câu hỏi
  jQuery('.quiz-skip-btn').click(function(){
    handleAnswer(null, true);
  });

  // ### nút: qua câu tiếp theo
  jQuery('.quiz-next').click(function(){
    if(questionType == 'cauhoi_tuluan'){
      handleAnswer(jQuery('#textAnswer').val().trim().toLowerCase());
    }else{
      const selected = [...document.querySelectorAll('input[name="multi"]:checked')].map(i => i.value);
      handleAnswer(selected);
    }
  });
  

  // ### chọn đáp án ---> xử lý lưu lịch sử trả lời
  function handleAnswer(answer, skip = false) {
    const q = questions[currentQuestion];
  
    if (skip) {
      answers[currentQuestion] = { answer: null, skip: true };
    } else {
      answers[currentQuestion] = { answer, skip: false };
    }

    score = 0;
    answers.forEach((ans, idx) => {
      const qx = questions[idx];
      if (ans && !ans.skip) {
        let correct = Array.isArray(qx.correct) ? qx.correct.map(c => c.toLowerCase()) : [qx.correct.toLowerCase()];
  
        if (qx.type === 'choice' || qx.type === 'multi-choice') {
          const answerSet = ans.answer.map(a => a.toLowerCase());
          var score_tmp = 0;
          var count_correct_option = 0;
          var is_answer_wrong = false;

          answerSet.forEach(answer => {
            // Kiểm tra xem phần tử đó có tồn tại trong mảng đáp án đúng hay không
            if (correct.includes(answer)) {
              score_tmp+=qx.score;  // Cộng điểm nếu đúng
              count_correct_option++;
            }else{
              is_answer_wrong = true;
            }
          });

          // ### Tình huống: Kiểm tra số đáp án chọn: nếu bằng số đáp án admin quy định thì tính điểm full 1000 (mặc định), ngược lại thì cộng theo số điểm trên mỗi đáp án
          if(count_correct_option==qx.correct.length){
            score_tmp = 1000;
          }

          // ### Tình huống: Nếu 1 câu hỏi có 3 đáp án đúng, chỉ cần tồn tại 1 đáp án sai trong số đáp án mà người chơi chọn ===> ko tính điểm
          if(is_answer_wrong){
            score_tmp = 0;
          }

          // Cộng điểm sau mỗi câu hỏi
          score+=score_tmp; 
          
        }else if (qx.type === 'text') {
          if (correct.includes(ans.answer)) score+=qx.score;
        }
      }
    });
  
    localStorage.setItem('answers', JSON.stringify(answers));
    localStorage.setItem('currentQuestion', currentQuestion + 1);
    localStorage.setItem('score', score);
  
    currentQuestion++;
    setTimeout(() => {
      if (currentQuestion < questions.length && getTimeLeft() > 0) {
        loadQuestion();
      } else {
        showScore();
      }
    }, 500);
  }
  
  function showScore() {
    clearInterval(timerInterval);

    const timeUsed = totalTime - getTimeLeft();
  
    let popupHtml = `<div id="quiz-popup-container"  class="quiz-popup-container" style="display:none">`;
      popupHtml += `<div class="quiz-popup-box">`;
        popupHtml += `<h2>Điểm số</h2>`;
        popupHtml += `<div>`;
        popupHtml += `<p class="quiz-box-score">${score}</p>`;
        popupHtml += `<p class="quiz-box-time">Thời gian thi: <strong>${formatTime(timeUsed)}</strong></p>`;
        popupHtml += `</div>`;
        //popupHtml += `<p>Thời gian còn lại: ${formatTime(getTimeLeft())}</p>`;
        //popupHtml += `<hr><div style="text-align:left;">`;
      
        /*questions.forEach((q, idx) => {
          const ans = answers[idx];
          popupHtml += `<p><strong>Câu ${idx + 1}:</strong> ${q.question}</p>`;
      
          if (ans?.skip) {
            popupHtml += `<p style="color: gray;"><em>Người chơi bỏ qua câu hỏi này.</em></p>`;
          } else {
            let selected = ans?.answer;
            if (Array.isArray(selected)) selected = selected.join(', ');
            popupHtml += `<p><strong>Đáp án đã chọn:</strong> ${selected ?? 'Không có'}</p>`;
          }
          popupHtml += `<hr>`;
        });*/
      
        popupHtml += `<button onclick="resetGame()">Hoàn thành</button>`;
      popupHtml += `</div>`;
    popupHtml += `</div>`;


    // ### lấy data kết quả --> lưu lại
    // let result_test
    questions.forEach((q, idx) => {
      const ans = answers[idx];

      var tmp_question = q.question;
      var tmp_options = ''; 
      var tmp_skip = 0;
      var tmp_select = '';
      var tmp_traloi_tuluan = '';

      for (const key in q.options) {
        const label = document.createElement('label');
        label.innerHTML = `<input type="radio" name="multi" value="${key}"> <span>${key}. ${q.options[key]}</span>`;
        label.style.display = 'flex';
        label.classList.add("answer-label", "answer-label-"+key);
        //container.appendChild(label);
        jQuery('.quiz-box-2_1').find('.quiz-box-left').append(label);
      }

      if (q.options !== null && q.options !== undefined) {
        tmp_options = q.options;
      }      
  
      if (ans?.skip) {
        tmp_skip = 1; // --> Người chơi bỏ qua câu hỏi
      } else {
        let selected = ans?.answer;
        tmp_select = selected;
        /*if (Array.isArray(selected)) {         
          tmp_select = selected;
        }*/
      }

      q.audio = (q.audio!='') ? q.audio : '';
      q.video = (q.video!='') ? q.video : '';
      q.image = (q.image!='') ? q.image : '';
      //console.log(q);
      //q.image = (q.image!='') ? q.image : '';
      
      result_test.push({ question: tmp_question,correct: q.correct, options: tmp_options, skip: tmp_skip, selected: tmp_select, audio: q.audio, video: q.video, image: q.image, type: q.type, question_display_type: q.question_display_type });
    });

    localStorage.clear();
  
    saveResultToServer(popupHtml);
  }
  
  function saveResultToServer(popupHtml) {
    const result = {
      idCategory,
      idPost,
      score,
      timeUsed: totalSeconds - getTimeLeft(),
      result_test
    };


    var ajaxurl = jQuery('#admin-url').val();
    const user = window.userID;

    // ### Hiển thị popup thông báo điểm số
    document.getElementById('popup').innerHTML = popupHtml;
    document.getElementById('popup').style.display = 'flex';

    // Hiện loading trước khi hiện thông tin điểm số
    document.getElementById('popup').insertAdjacentHTML('beforeend', document.querySelector('.loading-contain2').innerHTML);

    // Tắt nút close
    document.querySelector('.gameplay-close').style.display = 'none';


    // Gọi AJAX tới WordPress
    jQuery.ajax({
        url: ajaxurl, // WordPress sẽ tự động cung cấp biến này trong admin panel
        method: 'POST',
        data: {
            action: 'process_quiz_result',  // Tên action mà bạn sẽ xử lý trong functions.php
            result: JSON.stringify(result) // Dữ liệu bạn muốn gửi lên
        },
        success: function(response) {
          if(user!=0){
            fetch(ajaxurl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=remove_user_code&user_code=' + encodeURIComponent(user)
            })
            .then(res => res.json())
            .then(data => {
              // ajax thành công ---> xóa loading
              var element = document.querySelector('.loading-svg');              
              if (element && element.closest('#popup')) {
                element.remove();
              }

              // Mở nút close
              var element_close = document.querySelector('.gameplay-close');
              if(element_close){
                element_close.style.display = 'inline-flex';
                element_close.classList.add('done-game');    // Thêm class 'done-game'
                element_close.classList.remove('in-game');   // Xóa class 'in-game'
              }              

              // ajax thành công ---> hiển thị thông tin điểm số
              document.getElementById('quiz-popup-container').style.display = 'flex';
            });
          }
        },
        error: function(error) {
            //console.log('Có lỗi xảy ra:', error);
        }
    });
  }

  // ### reset game và reload page
  function resetGame() {
    localStorage.clear();
    location.reload();

    /*gameStarted = false;
    jQuery('#gameplay').removeClass('open-game').addClass('hidden-game');
    jQuery("#gameplay-middle").empty();
    jQuery('.gameplay-close').removeClass('in-game');    
    jQuery("#gameplay-middle").empty();
    jQuery('#topic-choosed').val(0);
    jQuery('#baithi-choosed').val(0);*/
  }

  // ### Xử lý khi người dùng nhấn nút close - dừng game : nếu đang trong game thì sẽ hỏi xác nhận
  // jQuery('.gameplay-close').click(function(){
  //   if(jQuery('.gameplay-close').hasClass('in-game')){
  //     if (confirm("Bạn muốn dừng trò chơi?")) {
  //       // Code to execute if 'Yes' is clicked
  //       resetGame();
  //     }
  //   }    
  // });

  // Ngăn reload trang khi người dùng nhấn F5 hoặc Ctrl + R / Ctrl + F5
  // window.addEventListener("keydown", function(event) {
  //     // Kiểm tra tổ hợp phím F5, Ctrl + R, Ctrl + F5
  //     if ((event.key === "F5") || (event.ctrlKey && event.key === "r")) {
  //         event.preventDefault();  // Ngăn sự kiện reload

  //         if (confirm("Tải lại trang sẽ dừng lại quá trình chơi game !")) {
  //           // Code to execute if 'Yes' is clicked
  //           resetGame();
  //         }
  //     }
  // });


  //Bắt đầu sau khi load ds câu hỏi
  startTime = Date.now();
  localStorage.setItem('startTime', startTime);
  document.getElementById('startButton').style.display = 'none';
  document.getElementById('timer').style.display = 'block';
  //gameStarted = true;
  loadQuestion();

  
  // Bắt đầu khi nhấn nút
  /*if (!gameStarted) {
    document.getElementById('startButton').addEventListener('click', () => {
      startTime = Date.now();
      localStorage.setItem('startTime', startTime);
      document.getElementById('startButton').style.display = 'none';
      document.getElementById('timer').style.display = 'block';
      gameStarted = true;
      loadQuestion();
    });
  
  } else {
    console.log(gameStarted);
    document.getElementById('startButton').style.display = 'none';
    document.getElementById('timer').style.display = 'block';
    loadQuestion();
  }*/


/*let isReloading = false;

  // Phát hiện khi người dùng nhấn F5 hoặc Ctrl + R để reload trang
  window.addEventListener("keydown", function(event) {
      if ((event.key === "F5") || (event.ctrlKey && event.key === "r")) {
          isReloading = true;
      }
  });

  // Phát hiện khi người dùng cố gắng đóng cửa sổ hoặc tab
  window.addEventListener("beforeunload", function(event) {
      if (isReloading) {
          console.log("Người dùng đang reload trang.");
      } else {
          console.log("Người dùng đang đóng cửa sổ hoặc tab.");
      }

      // Bạn có thể thông báo cho người dùng trước khi đóng (hoặc reload) trang
      const confirmationMessage = "Bạn có chắc chắn muốn rời khỏi trang này?";
      event.returnValue = confirmationMessage;  // Để trình duyệt hiển thị thông báo mặc định
      return confirmationMessage;  // Trả về thông điệp để trình duyệt hiển thị
  });

  // Cập nhật lại trạng thái khi trang đã reload hoàn tất
  window.addEventListener("load", function() {
      isReloading = false;
  });*/