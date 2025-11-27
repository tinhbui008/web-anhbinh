<?php

function gameplay_shortcode() {
    ob_start();

?>

<!-- BOX SHOW GAME PLAY -->
<div id="gameplay" class="gameplay-container hidden-game">
    <div class="gameplay-top">
        <span class="gameplay-close"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M18 2L2 18M2 2L18 18" stroke="#3D3D3D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
    </div>
    <div id="gameplay-middle" class="gameplay-middle"></div>
    <div id="gameplay-bottom" class="gameplay-bottom"></div>
</div>


<!-- BOX HIDDEN GAME -->
<div style="display:none">
    <!-- ### BOX 1 -->
    <div class="gameplay-box-1">
        <!-- CONTENT -->
        <div class="gameplay-box-1-content">
            <!-- SHORTCODE PUSHER FOLDER  -->
            <?=do_shortcode('[user_code_form]')?>
        </div>
        <!-- BUTTON -->
        <div class="gameplay-box-1-buttons">
            <div class="gameplay-button" data-post="1"><span>Tiếp theo</span><svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M7.43333 4.2C7.96667 4.6 7.96667 5.4 7.43333 5.8L2.1 9.8C1.44076 10.2944 0.5 9.82404 0.5 9L0.5 1C0.5 0.175955 1.44076 -0.294428 2.1 0.2L7.43333 4.2Z" fill="white"/> </svg></div>
        </div>
    </div>


    <!-- ### BOX 2 -->
    <div class="gameplay-box-2">
        <div class="quiz-main">
            <!-- <div id="timer"></div> -->
            <button id="startButton">Bắt đầu trò chơi</button>
            <div id="questionProgress"><span id="questionProgress-active"></span></div>
            <div class="question-box">
                <div id="questionText"></div>
                <!-- COUNT QUESTIONS -->
                <div class="question-count">
                    <span id="question-current"></span>/<span id="question-length"></span>
                </div>
                <!-- AUDIO -->
                <div id="question-audio"></div>
            </div>
            <div id="answerText">Câu trả lời:</div>
            <div id="answerButtons"></div>
            <div id="result"></div>
            <div id="popup"></div>
        </div>
    </div>


    <!-- ### BOX 2.1 - loại câu hỏi có hình ảnh hoặc video -->
    <div class="gameplay-box-2_1 div-hidden">
        <div class="quiz-box-2_1">
           <div class="quiz-box-left"></div>
           <div class="quiz-box-right"><img src="" alt="cauhoi_image"></div>
        </div>
        <div class="quiz-box-2_2">
            <audio controls>
                <source src="">
                Your browser does not support the audio element.
            </audio>
        </div>
        <div class="quiz-box-2_3">
            <video width="100%" height="100%" controls><source src="" type="video/mp4"></video> 
        </div>
    </div>


    <!-- ### BOX 3 -->
    <div class="gameplay-box-3">
        <div class="quiz-buttons">
            <div class="quiz-buttons-left"><span class="quiz-back quiz-btn quiz-back-btn"><svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.566667 4.2C0.0333335 4.6 0.0333335 5.4 0.566667 5.8L5.9 9.8C6.55924 10.2944 7.5 9.82404 7.5 9L7.5 1C7.5 0.175955 6.55924 -0.294428 5.9 0.2L0.566667 4.2Z" fill="#3D3D3D"/> </svg>Quay lại</span></div>
            <div class="quiz-clock">
                <img src="<?=IMG?>/images/clock2.png" alt="clock">
                <div class="quiz-timeclock"><div id="timer"></div></div>
            </div>
            <div class="quiz-buttons-right">
                <span class="quiz-back quiz-btn quiz-skip-btn">Bỏ qua</span>
                <span class="quiz-btn quiz-next">Tiếp theo <svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.566667 4.2C0.0333335 4.6 0.0333335 5.4 0.566667 5.8L5.9 9.8C6.55924 10.2944 7.5 9.82404 7.5 9L7.5 1C7.5 0.175955 6.55924 -0.294428 5.9 0.2L0.566667 4.2Z" fill="#888888"/> </svg></span>
            </div>
        </div>
    </div>
    

    <!-- HIDDEN TYPE VALUE -->
    <input type="hidden" value="0" id="topic-choosed">
    <input type="hidden" value="0" id="baithi-choosed">
</div>


<?php

    return ob_get_clean();
}
add_shortcode('gameplay', 'gameplay_shortcode');