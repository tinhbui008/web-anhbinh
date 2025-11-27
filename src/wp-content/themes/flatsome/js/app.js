// ### XỬ LÝ : SCROLL BẢNG XẾP HẠNG VÀ LOAD MORE ITEMS DS NGƯỜI CHƠI
// jQuery(function ($) {
//     "use strict";

//     let loading = false;
//     let $container = $('#bangxephang-items-container');
//     let $loadData = $('#load-more-data');

//     $container.on('scroll', function() {

//         // Kiểm tra nếu đang load thì không gửi Ajax mới
//         if (loading) return;

//         // Kiểm tra nếu scroll đến cuối container
//         if ($container.scrollTop() + $container.innerHeight() >= $container[0].scrollHeight - 10) {
//             loading = true;
//             $('#loader').show();

//             let page = parseInt($loadData.data('page'));
//             let ajaxurl = $loadData.data('url');

//             $.ajax({
//                 url: ajaxurl,
//                 type: 'POST',
//                 dataType : "html",
//                 data: {
//                     action: 'loadbangxephang',
//                     page: page
//                 },
//                 success: function(response) {
//                     if ($.trim(response) !== '') {
//                         $container.append(response);
//                         $loadData.data('page', page + 1);
//                         loading = false;
//                         $('#loader').hide();
//                     } else {
//                         $('#loader').text('Không còn bài viết nào.');
//                     }
//                 }
//             });
//         }
//     });
// });


jQuery(document).ready(function($) {
    
    jQuery('.form-tuyendung-file input[type="file"]').on('change', function() {
        var fileName = $(this).val().split('\\').pop(); // lấy tên file từ đường dẫn
        jQuery('#fileNameDisplay').text('File đã chọn: '+fileName);
    });

});


// ### XỬ LÝ TUYỂN DỤNG AJAX
jQuery(document).ready(function($) {
    // Hàm gọi Ajax khi thay đổi giá trị filter
    function filterTuyenDung(paged = 1) {
        var searchKeyword = $('#tuyendung_search').val();
        var khuVuc = $('#tuyendung_khuvuc').val();
        var khoiVanPhong = $('#tuyendung_khoivanphong').val();
        var trangThai = $('#tuyendung_trangthai').val();
        var paged = $('#tuyendung-paged').val();

        const params = {
            //paged: paged,
            keyword: searchKeyword,
            khu_vuc: khuVuc,
            khoi_van_phong: khoiVanPhong,
            trang_thai: trangThai
        };

        // Tạo URL query mới
        // Lấy phần path gốc (không có query)
        let basePath = window.location.pathname.replace(/\/page\/\d+\/?$/, '');
        basePath = basePath.replace(/\/$/, ''); // <- Loại bỏ dấu "/" ở cuối (nếu có)

        const queryString = new URLSearchParams(params).toString();
        const newUrl = (paged === 1)
            ? `${basePath}/?${queryString}`
            : `${basePath}/page/${paged}/?${queryString}`;

        // Cập nhật URL trên browser
        history.pushState(params, '', newUrl);

        // Gọi AJAX
        var ajaxurl = jQuery('#admin-url').val();
        $.ajax({
            url: ajaxurl, // URL gọi AJAX
            method: 'GET',
            data: {
                action: 'filter_tuyendung',
                keyword: searchKeyword,
                khu_vuc: khuVuc,
                khoi_van_phong: khoiVanPhong,
                trang_thai: trangThai,
                paged: paged
            },
            beforeSend: function(){
                //Làm gì đó trước khi gửi dữ liệu vào xử lý
                $("#tuyendung_results").empty();
                $("#tuyendung_results").append( $('.loading-contain').html() );                      
            },
            success: function(response) {
                $('#tuyendung_results').html(response);
            }
        });
    }

    // Gọi filter khi người dùng thay đổi các trường tìm kiếm
    $('#tuyendung_khuvuc, #tuyendung_khoivanphong, #tuyendung_trangthai').on('change', function() {
        filterTuyenDung();
    });

    // Trigger khi click phân trang
    $('body').on('click', '#tuyendung_pagination .page-numbers', function(e) {
        e.preventDefault();

        const paged = $(this).attr('data-page');
        $('#tuyendung-paged').val(paged);
        
        filterTuyenDung(paged);
    });

    // KEY SEARCH PHÂN TRANG
    function debounce(func, delay) {
		let timer;
		return function (...args) {
			clearTimeout(timer);
			timer = setTimeout(() => func.apply(this, args), delay);
		};
	}
	$(document).ready(function () {
		$('#tuyendung_search').on('keyup', debounce(function () {
			filterTuyenDung();
		}, 500)); // 400ms sau khi ngừng gõ mới gửi
	});
});


// ### XỬ LÝ GAMEPLAY
jQuery(function ($) {
    "use strict";

    // NÚT OPEN MỞ KHUNG GAME
    $('.vnplay-btnplay').click(function(){

        if(window.USER_LOGGED==0){
            $('.dev-userLogin').trigger('click');
            return false;
        }

        $('#userCodeForm h2').text('Nhập mã PIN');
        $('#gameplay').removeClass('hidden-game').addClass('open-game');

        // OPEN BOX 1
        $("#gameplay-middle").append( $('.loading-contain2').html() ); 
        setTimeout(function() { 
            $("#gameplay-middle").html($('.gameplay-box-1').find('.gameplay-box-1-content').clone());
            $("#gameplay-bottom").html($('.gameplay-box-1').find('.gameplay-box-1-buttons').clone());
        }, 700);
    });


    // XỬ LÝ : TOPIC ITEM CLICK
    $('body').on('click', '.topic-item', function() {
        $('.topic-item').removeClass('topic-item-active');
        $(this).addClass('topic-item-active');

        var id = $(this).attr('data-id');
        $('#topic-choosed').val(id);
    });


    // XỬ LÝ : CLICK NÚT TIẾP THEO - BOX 1
    $('body').on('click', '.gameplay-button', function() {
        var pos = parseInt($(this).attr('data-post'));
       // console.log(pos);

        switch(pos) {
            case 1:
                loadDSbaithi(pos);
                break;
            case 2:
                //loadBocauhoi(pos);
                break;
        }
    });


    // FUNCTION : LOAD BÀI THI THEO TOPIC
    function loadDSbaithi(pos){
        // Cần kiểm tra chủ đề đã được chọn?
        //var topic_choosed = parseInt($('#topic-choosed').val());
        var user_code = $('input[name="user_code"]').val();

        if(user_code==''){ // Chưa chọn chủ đề ===> thông báo warning
            Swal.fire({
                title: "",
                text: "Bạn chưa nhập mã PIN !",
                icon: "error",
                confirmButtonText: "Đã hiểu",
            });
            return false;
        }else{
            // Xử lý AJAX : load ds bài thi dựa theo id chủ đề (lấy từ input #topic-choosed)
            var admin_url = $('#admin-url').val();

            $.ajax({
                type : "post", //Phương thức truyền post hoặc get
                dataType : "json", //Dạng dữ liệu trả về xml, json, script, or html
                url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
                data : {
                    action: "loadtopic", //Tên action
                    user_code : user_code,//Biến truyền vào xử lý. $_POST['dateCurrent']
                },
                context: this,
                beforeSend: function(){
                    //Làm gì đó trước khi gửi dữ liệu vào xử lý
                    //$("#gameplay-middle").empty();
                    //$("#gameplay-middle").append( $('.loading-contain2').html() );    
                    
                    $('#userList').append( $('.loading-contain2').html() );
                },
                success: function(response) {
                    $('#userList').empty();

                    //Làm gì đó khi dữ liệu đã được xử lý
                    if(response.success && response.data.userId!=0){

                        window.userID = response.data.userId;
                        window.userName = response.data.userName;
                        window.userAvatar = response.data.userAvatar;

                        $('.gameplay-button').attr('data-post', (pos+1));
                        $('.gameplay-button span').text('Đang chờ...');

                        //Thêm class cho nút close, sẽ sử dụng class này để kiểm tra tình trạng thoát game: nếu có class --> đã tham gia phòng chờ
                        $('.gameplay-close').addClass('gameplay-inRoom');

                        $.ajax({
                            type : "post", 
                            dataType : "json", 
                            url : admin_url, 
                            data : {
                                action: "submit_user_code",
                                userID : window.userID,
                                userName : window.userName,
                                userAvatar : window.userAvatar,
                            },
                            context: this,
                            beforeSend: function(){
                                $('#userList').append( $('.loading-contain2').html() );
                            },
                            success: function(response) {  
                                // Nếu người chơi đã cập nhật mã PIN thành công ---> đã đẩy lên pusher ---> lưu id user và session
                                // dùng cho mục đích khi người chơi reload page enter trên url trình duyệt
                                sessionStorage.setItem("user_added_pusher", window.USER_LOGGED);
                                
                                if(response.success){
                                    $('#userCodeForm input[name="user_code"]').addClass('input-hidden');
                                    $('#userList').find('.loading-svg').remove();
                                }else{
                                    $('#userList').find('.loading-svg').remove();
                                    $('.gameplay-button').attr('data-post', 1);
                                    $('.gameplay-button span').text('Tiếp theo');
                                    confirm(response.data.message);

                                    $('.gameplay-close').removeClass('gameplay-inRoom');
                                    $('.gameplay-button').attr('data-post', 1);
                                    $('#userCodeForm input[name="user_code"]').removeClass('input-hidden');                         
                                    $('#gameplay').removeClass('open-game').addClass('hidden-game');
                                    $("#gameplay-middle").empty();
                                    $('#topic-choosed').val(0);
                                    $('#baithi-choosed').val(0);
                                }                                
                                //updateUserList(response.data);
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                //console.error('Lỗi gửi form:', error);
                            }
                        }) 

                    }else{
                        //$('input[name="user_code"]').val();
                        if(response.data.userId==0){
                            //alert('Chưa đăng nhập tài khoản');
                            Swal.fire({
                                title: "",
                                text: "Bạn cần đăng nhập tài khoản !",
                                icon: "error",
                                confirmButtonText: "Đã hiểu",
                            });
                            return false;
                        }                        
                        //alert('Mã không hợp lệ');
                        Swal.fire({
                            title: "",
                            text: "Mã PIN không hợp lệ !",
                            icon: "error",
                            confirmButtonText: "Đã hiểu",
                        });
                    }
                    // if(response) {
                    //     $("#gameplay-middle").html( response );
                    //     $('.gameplay-button').attr('data-post', (pos+1));
                    //     $('.gameplay-button span').text('Bắt đầu');
                    // }
                    // else {
                    //     console.log('Đã có lỗi xảy ra');
                    // }
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    //Làm gì đó khi có lỗi xảy ra
                    //console.log('Mã không hợp lệ');
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                }
            })
        }
    }


    // FUNCTION : LOAD BÀI THI THEO TOPIC
    /*function loadBocauhoi(pos=0){
        // Cần kiểm tra bài thi đã được chọn?
        //var baithi_choosed = parseInt($('#baithi-choosed').val());

        // Xử lý AJAX : load ds bài thi dựa theo id chủ đề (lấy từ input #topic-choosed)
        var admin_url = $('#admin-url').val();
        $.ajax({
            type : "post", //Phương thức truyền post hoặc get
            dataType : "json", //Dạng dữ liệu trả về xml, json, script, or html
            url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
            data : {
                action: "loadbocauhoi", //Tên action
                //id : baithi_choosed,//Biến truyền vào xử lý. $_POST['dateCurrent']
            },
            context: this,
            beforeSend: function(){
                //Làm gì đó trước khi gửi dữ liệu vào xử lý
                $("#gameplay-middle").empty();
                $("#gameplay-middle").append( $('.loading-contain2').html() );                         
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                if(response) {
                    $("#gameplay-middle").html( $('.gameplay-box-2').clone() );   
                    $("#gameplay-bottom").html( $('.gameplay-box-3 .quiz-buttons').clone() );                        

                    // XỬ LÝ : CHẠY GAME QUIZ
                    //Tải và thực thi file JS sau khi gọi AJAX thành công
                    window.totalTime = response.data.totalTime;
                    window.questions = response.data.questions;
                    window.idPost = response.data.idPost;
                    window.idCategory = $('#topic-choosed').val(); 

                    var template_url = $('#template-url').val();
                    $.getScript(template_url+"quiz.js")
                        .done(function(script, textStatus) {
                            $('.gameplay-close').addClass('in-game');
                        })
                        .fail(function(jqxhr, settings, exception) {
                    });
                }
                else {
                    console.log('Đã có lỗi xảy ra');
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        })
    }*/


    // XỬ LÝ : BÀI THI ĐƯỢC CHỌN
    // $('body').on('click', '.gameplay-baithi-item', function() {
    //     $('.gameplay-baithi-item').removeClass('gameplay-baithi-item-active');
    //     $(this).addClass('gameplay-baithi-item-active');

    //     var id = $(this).attr('data-id');
    //     $('#baithi-choosed').val(id);
    // });


    // KIỂM TRA KEYDOWN KHI NGƯỜI DÙNG RELOAD PAGE
    $(document).on('keydown', function(event) {
        // Kiểm tra trên hệ đh window
        // if (((event.key === "F5") || (event.ctrlKey && event.key === "r")) && window.isGame == true) {
        //     event.preventDefault();  // Ngăn sự kiện reload

        //     $('.gameplay-close').trigger('click');
        // }   

        const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;

        const isReloadKey =
            event.key === "F5" || // F5 trên cả Mac/Windows
            (!isMac && event.ctrlKey && event.key === "r") || // Ctrl + R trên Windows
            (isMac && event.metaKey && event.key === "r");    // Cmd + R trên Mac

        if (isReloadKey && window.isGame === true) {
            event.preventDefault();  // Ngăn reload trang

            // Đóng game nếu đang chơi
            $('.gameplay-close').trigger('click');
        }

    });


    // NÚT CLOSE ĐÓNG KHUNG GAME
    $('body').on('click', '.gameplay-close', function() {

        if($('.gameplay-close').hasClass('done-game')){
            resetGame();
            return false;
        }

        Swal.fire({
            title: "Bạn muốn dừng lại ?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Đồng ý",
            denyButtonText: `Từ chối`
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $('.gameplay-button span').text('Tiếp theo');

                $('#gameplay-middle').empty();
                $("#gameplay-middle").append( $('.loading-contain2').html() );

                // Pusher khi người dùng thoát phòng chờ, kiểm tra dựa trên class gameplay-inRoom
                if($('.gameplay-close').hasClass('gameplay-inRoom')){
                    const user = window.userID;
                    var ajaxurl = jQuery('#admin-url').val();

                    if(user!=0){
                        fetch(ajaxurl, {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: 'action=remove_user_code&user_code=' + encodeURIComponent(user)
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Nếu đang trong game ---> reload page ---> reset data localStorage
                            if($('.gameplay-close').hasClass('in-game')){
                                //console.log('quit in game')
                                localStorage.clear();
                                location.reload();
                            }else{
                                $('.gameplay-close').removeClass('gameplay-inRoom');
                                $('.gameplay-button').attr('data-post', 1);
                                $('#userCodeForm input[name="user_code"]').removeClass('input-hidden');                         
                                $('#gameplay').removeClass('open-game').addClass('hidden-game');
                                $("#gameplay-middle").empty();
                                $('#topic-choosed').val(0);
                                $('#baithi-choosed').val(0);
                            }
                        });
                    }
                }else{ // Đóng trang nhập mã PIN
                    $('#gameplay').removeClass('open-game').addClass('hidden-game');
                    $("#gameplay-middle").empty();
                    $('#topic-choosed').val(0);
                    $('#baithi-choosed').val(0);
                }
            } else if (result.isDenied) {
                //Swal.fire("Changes are not saved", "", "info");
            }
        });

        // if (confirm("Bạn muốn thoát?")) {
        //     $('.gameplay-button span').text('Tiếp theo');

        //     $('#gameplay-middle').empty();
        //     $("#gameplay-middle").append( $('.loading-contain2').html() );

        //     // Pusher khi người dùng thoát phòng chờ, kiểm tra dựa trên class gameplay-inRoom
        //     if($('.gameplay-close').hasClass('gameplay-inRoom')){
        //         const user = window.userID;
        //         var ajaxurl = jQuery('#admin-url').val();

        //         if(user!=0){
        //             fetch(ajaxurl, {
        //                 method: 'POST',
        //                 headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        //                 body: 'action=remove_user_code&user_code=' + encodeURIComponent(user)
        //             })
        //             .then(res => res.json())
        //             .then(data => {
        //                 // Nếu đang trong game ---> reload page ---> reset data localStorage
        //                 if($('.gameplay-close').hasClass('in-game')){
        //                     //console.log('quit in game')
        //                     localStorage.clear();
        //                     location.reload();
        //                 }else{
        //                     $('.gameplay-close').removeClass('gameplay-inRoom');
        //                     $('.gameplay-button').attr('data-post', 1);
        //                     $('#userCodeForm input[name="user_code"]').removeClass('input-hidden');                         
        //                     $('#gameplay').removeClass('open-game').addClass('hidden-game');
        //                     $("#gameplay-middle").empty();
        //                     $('#topic-choosed').val(0);
        //                     $('#baithi-choosed').val(0);
        //                 }
        //             });
        //         }
        //     }else{ // Đóng trang nhập mã PIN
        //         $('#gameplay').removeClass('open-game').addClass('hidden-game');
        //         $("#gameplay-middle").empty();
        //         $('#topic-choosed').val(0);
        //         $('#baithi-choosed').val(0);
        //     }

        //     // if(!$('.gameplay-close').hasClass('in-game')){
                
        //     // }else{
        //     //     $('#gameplay').removeClass('open-game').addClass('hidden-game');
        //     //     $("#gameplay-middle").empty();
        //     //     $('#topic-choosed').val(0);
        //     //     $('#baithi-choosed').val(0);
        //     //     localStorage.clear();
        //     //     location.reload();
        //     // }
        // }        
    });

});




// ### THỜI GIAN BÌNH CHỌN TRANG VNPLAY
jQuery(function ($) {
    "use strict";
    //var distance; // Biến toàn cục

    var serverNow = parseInt($('#timeserver').val()) * 1000;     // PHP time -> JS timestamp (ms)
    var endTime = parseInt($('#thoigianbinhchon').val()) * 1000; // Convert to milliseconds for JS
    var clientLoadTime = new Date().getTime();

    function updateCountdown() {
        var now = new Date().getTime();
        var elapsed = now - clientLoadTime;
        var currentTime = serverNow + elapsed; // Thời gian thực dựa vào thời điểm server

        window.distance = endTime - currentTime;

        if (window.distance < 0) {
            $('#countdown').text("Đã kết thúc.");
            clearInterval(timer);
            return;
        }

        var days = Math.floor(window.distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((window.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((window.distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((window.distance % (1000 * 60)) / 1000);

        $('#countdown').text(
            "Kết thúc sau " + ((days>0) ? days + " ngày "  : '') +
            ("0" + hours).slice(-2) + ":" +
            ("0" + minutes).slice(-2) + ":" +
            ("0" + seconds).slice(-2)
        );
    }

    var timer = setInterval(updateCountdown, 1000);
    updateCountdown(); // Gọi lần đầu
});



// ### AJAX popup bình chọn
jQuery(function ($) {
    "use strict";

    $('body').on('click', '.vnplay-btn, .popup-binhchon-prev, .popup-binhchon-next', function() {

        if(window.USER_LOGGED==0){
            jQuery('.vnplay-btnplay').trigger('click');
            return false;

            // Swal.fire({
            //     title: "",
            //     text: "Cần đăng nhập tài khoản để có quyền bình chọn !",
            //     icon: "error",
            //     confirmButtonText: "Đã hiểu",
            // });
            // return false;
        }
        
        
        var id = $(this).attr('data-id');

        if($('.popup-binhchon-contain').hasClass('popup-binhchon-contain-active')){
            $('.popup-binhchon-contain').removeClass('popup-binhchon-contain-active');

            setTimeout(function() { 
                loadPost(id);
            }, 500);
            
        }else{
            loadPost(id);
        }
        
        return false;
    });


    function loadPost(id){
        var admin_url = $('#admin-url').val();

        if (window.distance < 0) {
            $(".popup-binhchon-ajax").empty().append('<div class="popup-binhchon-timeout"><div><span class="popup-binhchon-close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M18 6L6 18M6 6L18 18" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span></div>Đã hết thời gian bình chọn !</div>');

            $("#popup-binhchon").addClass( 'popup-binhchon-ajax-active' );
            return false;
        }


        // Call ajax nếu còn thời gian bình chọn
        $.ajax({
            type : "post", //Phương thức truyền post hoặc get
            dataType : "html", //Dạng dữ liệu trả về xml, json, script, or html
            url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
            data : {
                action: "binhchon", //Tên action
                id : id,//Biến truyền vào xử lý. $_POST['dateCurrent']
            },
            context: this,
            beforeSend: function(){
                //Làm gì đó trước khi gửi dữ liệu vào xử lý
                $(".popup-binhchon-ajax").empty();
                $(".popup-binhchon-ajax").append( $('.loading-contain2').html() ); 
                $("#popup-binhchon").addClass( 'popup-binhchon-ajax-active' );
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                if(response) {
                    $(".popup-binhchon-ajax").html( response );
                    setTimeout(function() { 
                        $(".popup-binhchon-contain").addClass( 'popup-binhchon-contain-active' );  
                    }, 300);
                }
                else {
                    console.log('Đã có lỗi xảy ra');
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        })
    }


    // PLAY VIDEO
    $('body').on('click', '.popup-binhchon-video', function() {
        console.log('video');
        if(!$(this).hasClass('popup-binhchon-video-active')){
            $(this).addClass('popup-binhchon-video-active');
            $('#popup-binhchon-videoBox')[0].play();
        }else{
            $(this).removeClass('popup-binhchon-video-active');
            $('#popup-binhchon-videoBox')[0].pause();
        }
    });


    // CLOSE POPUP
    $('body').on('click', '.popup-binhchon-close', function() { 
        $(".popup-binhchon-contain").removeClass( 'popup-binhchon-contain-active' );
        setTimeout(function() {
            $("#popup-binhchon").removeClass( 'popup-binhchon-ajax-active' );
            $(".popup-binhchon-ajax").html();
        }, 300);
    });


    $('body').on('click', '.popup-binhchon-btn', function() {
        var id = $(this).attr('data-id');
        var admin_url = $('#admin-url').val();

        $.ajax({
            type : "post", //Phương thức truyền post hoặc get
            dataType : "json", //Dạng dữ liệu trả về xml, json, script, or html
            url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
            data : {
                action: "luubinhchon", //Tên action
                id : id,//Biến truyền vào xử lý. $_POST['dateCurrent']
            },
            context: this,
            beforeSend: function(){
                $('.popup-binhchon-btn').addClass('popup-binhchon-btn-svg');
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                if(response.success) {
                    $('.popup-binhchon-btn').removeClass('popup-binhchon-btn-svg');

                    if(!$('.popup-binhchon-btn').hasClass('has-binhchon')){
                        $('.popup-binhchon-btn').addClass('has-binhchon');
                        $('.popup-binhchon-btn span').text('Đã bình chọn');
    
                        $('.vnplay-btn-'+id).addClass('has-binhchon');
                        $('.vnplay-btn-'+id+' span').text('Đã bình chọn');
                    }else{
                        $('.popup-binhchon-btn').removeClass('has-binhchon');
                        $('.popup-binhchon-btn span').text('Bình chọn');
    
                        $('.vnplay-btn-'+id).removeClass('has-binhchon');
                        $('.vnplay-btn-'+id+' span').text('Bình chọn');
                    }

                    $('.popup-binhchon-bottom .vnplay-luotbinhchon span').text(response.data.luotbinhchon);
                    $('.vnplay-item-'+id).find('.vnplay-luotbinhchon span').text(response.data.luotbinhchon);
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                // Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        })
        return false;
    });
    

});


// ### AJAX change date calendar
jQuery(function ($) {
    "use strict";

    $(document).ready(function () {
        let syncing = false;

        $('.s-sukien-topMain').on('scroll', function () {
            if (!syncing) {
                syncing = true;
                $('.s-sukien-dates-main').scrollLeft($(this).scrollLeft());
                syncing = false;
            }
        });

        $('.s-sukien-dates-main').on('scroll', function () {
            if (!syncing) {
                syncing = true;
                $('.s-sukien-topMain').scrollLeft($(this).scrollLeft());
                syncing = false;
            }
        });
    });


    $('body').on('click', '.s-sukien-btn-submit', function() {
        var dateCurrent = $(this).attr('data-date');
        var type = $(this).attr('data-type');
        var admin_url = $('#admin-url').val();

        console.log(type);

        $.ajax({
            type : "post", //Phương thức truyền post hoặc get
            dataType : "html", //Dạng dữ liệu trả về xml, json, script, or html
            url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
            data : {
                action: "changeDate", //Tên action
                dateCurrent : dateCurrent,//Biến truyền vào xử lý. $_POST['dateCurrent']
                type : type // Phân biệt sự kiện cho trang chủ và trang đào tạo
            },
            context: this,
            beforeSend: function(){
                //Làm gì đó trước khi gửi dữ liệu vào xử lý
                $('#s-sukien-containBox').empty();
                $("#s-sukien-containBox").append( $('.loading-contain').html() );
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                if(response) {
                    $('#ajax-show-sukien').html(response);
                    requestAnimationFrame(() => {
                        initTooltips();
                    });

                    $(document).ready(function () {
                        let syncing = false;

                        $('.s-sukien-topMain').on('scroll', function () {
                            if (!syncing) {
                                syncing = true;
                                $('.s-sukien-dates-main').scrollLeft($(this).scrollLeft());
                                syncing = false;
                            }
                        });

                        $('.s-sukien-dates-main').on('scroll', function () {
                            if (!syncing) {
                                syncing = true;
                                $('.s-sukien-topMain').scrollLeft($(this).scrollLeft());
                                syncing = false;
                            }
                        });
                    });
                }
                else {
                    console.log('Đã có lỗi xảy ra');
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        })
        return false;
    });
});


// ### AJAX get post DAOTAO
jQuery(function ($) {
    "use strict";

    $('body').on('click', '.s-daotao-tab', function() {
        var id_cat = $(this).attr('data-id');
        var admin_url = $('#admin-url').val();

        $('.s-daotao-tab').removeClass('s-daotao-tab-active');
        $(this).addClass('s-daotao-tab-active');

        $.ajax({
            type : "post", //Phương thức truyền post hoặc get
            dataType : "html", //Dạng dữ liệu trả về xml, json, script, or html
            url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
            data : {
                action: "getpostDaotao", //Tên action
                id_cat : id_cat,//Biến truyền vào xử lý. $_POST['dateCurrent']
            },
            context: this,
            beforeSend: function(){
                //Làm gì đó trước khi gửi dữ liệu vào xử lý
                $('#tab-show-daotao').empty();
                $("#tab-show-daotao").append( $('.loading-contain').html() );
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                if(response) {
                    $('#tab-show-daotao').html(response);
                }
                else {
                    console.log('Đã có lỗi xảy ra');
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        })
        return false;
    });

    let firedEvents = [];

    $(window).scroll(function() {
        $(".s-daotao-container").each(function() {
        if (!firedEvents.includes(this) && $(window).scrollTop() > $(this).offset().top - 500) {
            firedEvents.push(this);
            var e_active = $(this).find('.s-daotao-tab').eq(0);
            e_active.trigger('click');
        }
        });
    });
});



// ### GIÁ TRỊ CỐT LÕI
jQuery(function ($) {
    "use strict";

    $('.giatricotloi-item').click(function(){
        var photo = $(this).find('.giatricotloi-photo').attr('data-photo');
        var title = $(this).find('.giatricotloi-title').text();
        var content = $(this).find('.giatricotloi-content').text();

        $('.giatricotloi-main-title h3').text(title);
        $('.giatricotloi-main-title p img').attr('src',photo);
        $('.giatricotloi-main-content').text(content);
    });
});


// ### LỊCH SỬ HÌNH THÀNH
jQuery(function ($) {
    "use strict";

    $(function () {
        $("div.lichsuhinhthanh-item").slice(0, 5).show();
        $("#loadMore").on('click', function (e) {
            e.preventDefault();

            $("div.lichsuhinhthanh-item:hidden").slice(0, 5).slideDown();
            if ($("div.lichsuhinhthanh-item:hidden").length == 0) {
                $("#loadMore").fadeOut('slow');
            }
            $('html,body').animate({
                scrollTop: $(this).offset().top - 220
            }, 1500);
        });
    });

    $('a[href=#top]').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 600);
        return false;
    });
});


jQuery(function ($) {
    "use strict";

    // $('.s-intro-video').click(function(){
    //     $('.s-intro-video a.button').trigger('click');
    // });


    // BUTTON : SÁCH HAY
    $(window).on('load', function () {

        setTimeout(function () {
            // BUTTON : CÁC ĐÀO TẠO
            if($('.daotao-sach-list').hasClass('is-draggable')){
                $('.daotao-sach-main').append('<span class="daotao-sach-prev"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M20 24L12 16L20 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>');
    
                $('.daotao-sach-main').append('<span class="daotao-sach-next"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12 24L20 16L12 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>');
    
                $('.daotao-sach-prev').click(function(){
                    $('.daotao-sach-main .flickity-prev-next-button.previous').trigger('click');
                });
                $('.daotao-sach-next').click(function(){
                    $('.daotao-sach-main .flickity-prev-next-button.next').trigger('click');
                });
            }

            // BUTTON : CÁC KHÓA ĐÀO TẠO
            if($('.khoadaotao-list').hasClass('is-draggable')){
                $('.khoadaotao-main').append('<span class="khoadaotao-prev"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M20 24L12 16L20 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>');
    
                $('.khoadaotao-main').append('<span class="khoadaotao-next"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12 24L20 16L12 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>');
    
                $('.khoadaotao-prev').click(function(){
                    $('.khoadaotao-main .flickity-prev-next-button.previous').trigger('click');
                });
                $('.khoadaotao-next').click(function(){
                    $('.khoadaotao-main .flickity-prev-next-button.next').trigger('click');
                });
            }
        }, 500);
        
        $('.s-sukien-container').append('<span class="s-sukien-layout"></span>');
        $('.daotao-sach-container').append('<span class="daotao-sach-layout"></span>');
        $('.daotao-sach-container').append('<span class="daotao-sach-icon1"></span>');
        $('.daotao-sach-container').append('<span class="daotao-sach-icon2"></span>');
        

    });


    // MENU
    // $( "#main-menu ul" ).clone().prependTo( "#showMenu-fixed" );
    // $('.cs-menu-btn').click(function(){
    //     $('#boxMenu-fixed').addClass('showMenu-fixed-active');
    // });

    // $('.cs-menu-close').click(function(){
    //     $('#boxMenu-fixed').removeClass('showMenu-fixed-active');
    // });


    // $('.faq-item').click(function(){
    //   var id = $(this).attr('data-id');     
    //   var photo = $(this).attr('data-photo');     
    //   $('.faq-item').removeClass('faq-item-active'); 

    //   $('.ourteam-photo').addClass('ourteam-photo-hidden');
    //   setTimeout(function() { 
    //     $('.ourteam-photo').find('img').attr('src',photo);
    //     $('.ourteam-photo').removeClass('ourteam-photo-hidden');
    //   }, 500);

    //   $(this).addClass('faq-item-active'); 
    //   $('.faq-content-active').slideToggle();
    //   $('.faq-content').removeClass('faq-content-active');
    //   $('#faq-content-'+id).slideToggle().addClass('faq-content-active');
    // });
    
});


jQuery(function ($) {
    "use strict";


    // COUNTER
    $('.counter').countUp({
        'time': 2000,
        'delay': 10
    });


    //$('.aos-init').addClass('aos-animate');
    // $('.aos-fade-right').attr('data-aos', 'fade-right');
    // $('.aos-fade-left').attr('data-aos', 'fade-left');
    // $('.aos-fade-up').attr('data-aos', 'fade-up');

    $(window).on('load', function () {
        AOS.init({
            duration: 1000, // Thời gian hiệu ứng
            offset:50,
            delay:0,
            once: false // Chỉ chạy hiệu ứng một lần khi phần tử được load
        });   
    });


    var swiper_daotao = new Swiper(".swiper-daotao", {
		slidesPerView: 4,
		spaceBetween: 24,
		loop: false,
		// autoplay: {
		// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
		// 	delay: 3000, // set slide delay time
		// },
		speed: 800,
        navigation: {
            nextEl: ".daotao-btn-next",
            prevEl: ".daotao-btn-prev",
        },
		breakpoints: {
			0: {
				slidesPerView: 2,
				spaceBetween: 12,
			},
			650: {
				slidesPerView: 3,
				spaceBetween: 20,
			},
			850: {
				slidesPerView: 4,
				spaceBetween: 24,
			}
		},
	});
	swiper_daotao.on('transitionEnd', function() {
		swiper_daotao.autoplay.start();
	});


    var swiper_thuvien = new Swiper(".swiper-thuvien", {
		slidesPerView: 1,
		spaceBetween: 24,
		loop: false,
		// autoplay: {
		// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
		// 	delay: 3000, // set slide delay time
		// },
        effect: 'fade',
		speed: 800,
        navigation: {
            nextEl: ".thuvien-btn-next",
            prevEl: ".thuvien-btn-prev",
        },
	});
	// swiper_thuvien.on('transitionEnd', function() {
	// 	swiper_thuvien.autoplay.start();
	// });


    // var swiper_carousel = new Swiper(".mySwiper-carousel", {
	// 	slidesPerView: 3,
	// 	spaceBetween: 24,		
    //     centeredSlides: true,
    //     roundLengths: true,
    //     loop: true,
    //     loopAdditionalSlides: 30,
	// 	// autoplay: {
	// 	// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 	// 	delay: 3000, // set slide delay time
	// 	// },
	// 	speed: 800,
    //     navigation: {
    //         nextEl: ".mySwiper-carousel-next",
    //         prevEl: ".mySwiper-carousel-prev",
    //     },
	// 	breakpoints: {
	// 		0: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
    //         651: {
	// 			slidesPerView: 3,
	// 			spaceBetween: 24,
	// 		}
	// 	},
	// });
	// swiper_carousel.on('transitionEnd', function() {
	// 	swiper_carousel.autoplay.start();
	// });


    // var swiper_slider = new Swiper(".mySwiper-slider", {
	// 	slidesPerView: 1,
	// 	spaceBetween: 0,
	// 	loop: true,
    //     effect: 'fade',
	// 	autoplay: {
	// 		//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 		delay: 3000, // set slide delay time
	// 	},
	// 	speed: 1000,
	// 	/*breakpoints: {
	// 		0: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		480: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		800: {
	// 			slidesPerView: 3,
	// 			spaceBetween: 12,
	// 		},
	// 		1024: {
	// 			slidesPerView: 1,
	// 			spaceBetween: 0,
	// 		},
	// 	},*/
	// });
	// swiper_slider.on('transitionEnd', function() {
	// 	swiper_slider.autoplay.start();

    //     // var img = $('.swiperTimer').find('.swiper-slide-active .time-swiper-img').attr('src');
    //     // var content = $('.swiperTimer').find('.swiper-slide-active .time-swiper-content').html();
    //     // $('.time-showBox-mobile').find('.time-showBox-img img').attr('src', img);
    //     // $('.time-showBox-mobile').find('.time-showBox-content').html(content);
	// });


    // var swiper_service = new Swiper(".mySwiper-service", {
	// 	slidesPerView: 2,
	// 	spaceBetween: 20,
	// 	loop: false,
	// 	// autoplay: {
	// 	// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 	// 	delay: 3000, // set slide delay time
	// 	// },
	// 	speed: 800,
    //     navigation: {
    //         nextEl: ".service-btn-next",
    //         prevEl: ".service-btn-prev",
    //     },
	// 	/*breakpoints: {
	// 		0: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		480: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		800: {
	// 			slidesPerView: 3,
	// 			spaceBetween: 12,
	// 		},
	// 		1024: {
	// 			slidesPerView: 1,
	// 			spaceBetween: 0,
	// 		},
	// 	},*/
	// });
	// swiper_service.on('transitionEnd', function() {
	// 	swiper_service.autoplay.start();
	// });


    // var swiper_solution = new Swiper(".mySwiper-solution", {
	// 	slidesPerView: 1,
	// 	spaceBetween: 20,
	// 	loop: true,
    //     effect: 'fade',
	// 	// autoplay: {
	// 	// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 	// 	delay: 3000, // set slide delay time
	// 	// },
	// 	speed: 800,
    //     navigation: {
    //         nextEl: ".solution-btn-next",
    //         prevEl: ".solution-btn-prev",
    //     },
	// });
	// swiper_service.on('transitionEnd', function() {
	// 	swiper_service.autoplay.start();
	// });


    // var swiper_transportion = new Swiper(".mySwiper-transportion", {
	// 	slidesPerView: 4,
	// 	spaceBetween: 0,
	// 	loop: false,
	// 	// autoplay: {
	// 	// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 	// 	delay: 3000, // set slide delay time
	// 	// },
	// 	speed: 800,
    //     // navigation: {
    //     //     nextEl: ".service-btn-next",
    //     //     prevEl: ".service-btn-prev",
    //     // },
	// 	/*breakpoints: {
	// 		0: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		480: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		800: {
	// 			slidesPerView: 3,
	// 			spaceBetween: 12,
	// 		},
	// 		1024: {
	// 			slidesPerView: 1,
	// 			spaceBetween: 0,
	// 		},
	// 	},*/
	// });
	// swiper_service.on('transitionEnd', function() {
	// 	swiper_service.autoplay.start();
	// });


    // var swiper_testimonial = new Swiper(".mySwiper-testimonial", {
	// 	slidesPerView: 4,
	// 	spaceBetween: 30,
	// 	loop: true,
	// 	// autoplay: {
	// 	// 	//reverseDirection: true, // slide delay: 3000, // slide delay: { autoplay: {
	// 	// 	//delay: 3000, // set slide delay time
	// 	// },
	// 	speed: 1000,
	// 	/*breakpoints: {
	// 		0: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		480: {
	// 			slidesPerView: 2,
	// 			spaceBetween: 12,
	// 		},
	// 		800: {
	// 			slidesPerView: 3,
	// 			spaceBetween: 12,
	// 		},
	// 		1024: {
	// 			slidesPerView: 1,
	// 			spaceBetween: 0,
	// 		},
	// 	},*/
	// });
	// swiper_testimonial.on('transitionEnd', function() {
	// 	swiper_testimonial.autoplay.start();
	// });



    // $(document).on("click", function (event) {
    //     if ($(event.target).closest(".nav-icon a").length === 0 && $(event.target).closest("#menuMobile-custom").length === 0 && $(event.target).closest(".header-search .button.icon.circle").length === 0 ) {
    //         $('#menuMobile-custom').removeClass('menuMobile-custom-active');
    //         $('body').removeClass('custom-searchForm');
    //         //$('.button.icon').removeClass('buttonMenu-active');   
    //     }
    // });

    //CUSTOM HOVER BUTTON
    // $('.header-search .button.icon.circle').click(function(){
    //     $('body').addClass('custom-searchForm');
    //     if($('.mobile-menuBtn').hasClass('dev-menuBtn-active')){
    //         $('.mobile-menuBtn').trigger('click');
    //     }
    // });



    // TRIGGER
    $(window).on('load', function(){
        $('.services-nav-prev').html('<span class="swiperDev-btn-prev service-btn-prev"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M1.55,28.68a1.86,1.86,0,0,0,0,2.64l11.9,11.91a1.87,1.87,0,1,0,2.65-2.65L5.52,30,16.1,19.42a1.87,1.87,0,1,0-2.65-2.65ZM59,28.13H2.87v3.74H59Z"></path></svg></span>');     
        $('.services-nav-next').html('<span class="swiperDev-btn-next service-btn-next"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M58.45,31.32a1.86,1.86,0,0,0,0-2.64L46.55,16.77a1.87,1.87,0,1,0-2.65,2.65L54.48,30,43.9,40.58a1.87,1.87,0,1,0,2.65,2.65ZM1,31.87H57.13V28.13H1Z"></path></svg></span>');   


        $('.services-nav-prev').click(function(){
            $('.flickity-prev-next-button.previous').trigger('click');
        });
        $('.services-nav-next').click(function(){
            $('.flickity-prev-next-button.next').trigger('click');
        });

        // $('.ourteam-nav-prev').click(function(){
        //     $('.flickity-prev-next-button.previous').trigger('click');
        // });
        // $('.ourteam-nav-next').click(function(){
        //     $('.flickity-prev-next-button.next').trigger('click');
        // });

        // $('.resource-nav-prev').click(function(){
        //     $('.flickity-prev-next-button.previous').trigger('click');
        // });
        // $('.resource-nav-next').click(function(){
        //     $('.flickity-prev-next-button.next').trigger('click');
        // });

    });


    // MARQUEE
    // $('.marquee-main .section-content').marquee({
    //     //duration in milliseconds of the marquee
    //     duration: 30000,
    //     //gap in pixels between the tickers
    //     gap: 110,
    //     //time in milliseconds before the marquee will start animating
    //     delayBeforeStart: 0,
    //     //'left' or 'right'
    //     direction: 'left',
    //     //true or false - should the marquee be duplicated to show an effect of continues flow
    //     duplicated: true,
    //     pauseOnHover: true
    // });


    var mydiv = $('.single-page');
        mydiv.find('.page-header-wrapper').insertBefore(mydiv);

    var mydiv = $('.content-area.page-wrapper');
        mydiv.find('.page-header-wrapper').insertBefore(mydiv);

    var mydiv = $('.theroomDetail-page');
        mydiv.find('.page-header-wrapper').insertBefore(mydiv);
    
    if ($(".page-header-wrapper")[0]){
        $('.banner-breadcrum-slug').remove();
    }

    // $('.dev-layoutRight-right .gallery-icon a img').each(function(){
    //   var img = $(this).attr('src');
    //   $(this).parent('a').attr('href', img);
    // });

    // $('.dev-layoutRight-right .gallery-icon a').attr('data-fancybox','gallery-photo');
        

    // FLEX HOVER DỰ ÁN
    // $('.duan-Box').hover(function(){
    //     $('.duan-Box').removeClass('duan-Box-active');
    //     $(this).addClass('duan-Box-active');
    // });

    
    // RELATIVE SERVICE
    var swiper_review = new Swiper(".swiper-relativeService", {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: false,
        //allowTouchMove: false,
        navigation: {
            nextEl: ".servicesSwiper-nav-next",
            prevEl: ".servicesSwiper-nav-prev",
        },
        // autoplay: {
        //  delay: 6000, // set slide delay time
        // },
        speed: 700,
        breakpoints: {
            0: {
                slidesPerView: 2,
                spaceBetween: 12,
                allowTouchMove: true,
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 12,
                allowTouchMove: true,
            },
            800: {
                slidesPerView: 3,
                spaceBetween: 24,
                allowTouchMove: true,
            },
            1252: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
});


// document.addEventListener('DOMContentLoaded', function () {
//     new Splide('.splide-top', {
//       autoWidth: true,
//       type   : 'loop',
//       drag   : 'false',
//       focus  : 'center',
//       perPage: 5,
//     //   autoScroll: {
//     //         speed: 0.8,
//     //   },
//       gap:60,
//       arrows:false,
//       breakpoints: {
//           0: {
//             perPage: 3,
//             gap    : 40,
//             autoWidth: false,
//           },
//           600: {
//             perPage: 3,
//             gap    : 40,
//             autoWidth: false,
//           },
//           800: {
//             perPage: 5,
//             gap    : 60,
//           },
//       },
//     }).mount( window.splide.Extensions );

//     new Splide('.splide-middle', {
//         autoWidth: true,
//         type   : 'loop',
//         drag   : 'false',
//         focus  : 'center',
//         perPage: 5,
//       //   autoScroll: {
//       //         speed: 0.8,
//       //   },
//         gap:60,
//         direction: 'rtl',
//         arrows:false,
//         breakpoints: {
//             0: {
//               perPage: 3,
//               gap    : 40,
//               autoWidth: false,
//             },
//             600: {
//               perPage: 3,
//               gap    : 40,
//               autoWidth: false,
//             },
//             800: {
//               perPage: 5,
//               gap    : 60,
//             },
//         },
//     }).mount( window.splide.Extensions );

//     new Splide('.splide-bottom', {
//         autoWidth: true,
//         type   : 'loop',
//         drag   : 'false',
//         focus  : 'center',
//         perPage: 5,
//         //   autoScroll: {
//         //         speed: 0.8,
//         //   },
//         gap:60,
//         arrows:false,
//         breakpoints: {
//             0: {
//               perPage: 3,
//               gap    : 40,
//               autoWidth: false,
//             },
//             600: {
//               perPage: 3,
//               gap    : 40,
//               autoWidth: false,
//             },
//             800: {
//               perPage: 5,
//               gap    : 60,
//             },
//         },
//     }).mount( window.splide.Extensions );
// });


// document.addEventListener('DOMContentLoaded', function () {
//     new Splide('.splide1', {
//       type   : 'loop',
//       drag   : 'free',
//       focus  : 'center',
//       perPage: 5,
//       autoScroll: {
//         speed: 1.2,
//       },
//       gap:24,
//       arrows:false,
//       breakpoints: {
//           0: {
//             perPage: 3,
//             gap    : 12,
//           },
//           600: {
//             perPage: 3,
//             gap    : 12,
//           },
//           800: {
//             perPage: 5,
//             gap    : 24,
//           },
//       },
//     }).mount( window.splide.Extensions );
// });


(function($) {
    $.fn.inputFilter = function(callback, errMsg) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
        if (callback(this.value)) {
          // Accepted value
          if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
            $(this).removeClass("input-error");
            this.setCustomValidity("");
          }
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          // Rejected value - restore the previous one
          $(this).addClass("input-error");
          this.setCustomValidity(errMsg);
          this.reportValidity();
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          // Rejected value - nothing to restore
          this.value = "";
        }
      });
    };
}(jQuery));


jQuery(function ($) {
    "use strict";

    $(document).ready(function() {
        $(".checkphone").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        },"Only digits allowed");

        $('.checkphone').keyup(function(){
            var phone1 = $('.checkphone1').val();
            var phone2 = $('.checkphone2').val();
            var phone3 = $('.checkphone3').val();
            var phone4 = $('.checkphone4').val();

            var phone = "("+phone1+")"+phone2+"-"+phone3+" ext. "+phone4;

            $('input[name="your-phonenumber"]').val(phone);
        });

        $(".checkphonealt").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        },"Only digits allowed");

        $('.checkphonealt').keyup(function(){
            var phone5 = $('.checkphone5').val();
            var phone6 = $('.checkphone6').val();
            var phone7 = $('.checkphone7').val();
            var phone8 = $('.checkphone8').val();

            var phone = "("+phone5+")"+phone6+"-"+phone7+" ext. "+phone8;

            $('input[name="your-alternatePhone"]').val(phone);
        });

        $(".checkzip").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        },"Only digits allowed");

        $('.checkzip').keyup(function(){
            var zip1 = $('.checkzip1').val();
            var zip2 = $('.checkzip2').val();
            var zipstate = $('select[name="zipstate"]').val();

            var zip = zipstate+" "+zip1+"-"+zip2;

            $('input[name="your-statezip"]').val(zip);
        });

        $('select[name="zipstate"]').change(function(){
            var zip1 = $('.checkzip1').val();
            var zip2 = $('.checkzip2').val();
            var zipstate = $('select[name="zipstate"]').val();

            var zip = zipstate+" "+zip1+"-"+zip2;

            $('input[name="your-statezip"]').val(zip);
        });
    });
    
    
    $('table').addClass('table_cover');
    
    $('img').each(function(){
        var alt = $(this).attr('alt');
        
        if(alt==''){
            $(this).attr('alt','photo')
        }
    });
    
    
    
});