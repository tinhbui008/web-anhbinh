function initTooltips() {
    const tooltip = document.getElementById("tooltipDev");
    const container = document.getElementById("gridContainer");
    const boxes = document.querySelectorAll(".s-sukien-item");
    const closeButton = document.getElementById("tooltip-close");
    const tooltipContent = document.getElementById("tooltip-show-content");

    const overlayTooltip = document.getElementById("overlay-tooltip");

    let currentBox = null;

    boxes.forEach((box, index) => {
        box.addEventListener("mouseenter", () => {           

            // Lấy thông tin box tương ứng và đặt vào tooltip
            const tooltipId = box.dataset.tooltipId;
            const contentEl = document.getElementById(tooltipId);

            // Copy nội dung HTML (không chỉ text)
            if (contentEl) {
                tooltipContent.innerHTML = contentEl.innerHTML;
                overlayTooltip.classList.add('show');
            }else{
                tooltip.classList.remove("show");
                overlayTooltip.classList.remove('show');
                return false;
            }

            currentBox = box;
        
            const boxRect = box.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
        
            const showLeft = boxRect.left > containerRect.left + containerRect.width / 2;
        
            tooltip.classList.remove("left", "right");
            tooltip.classList.add(showLeft ? "left" : "right", "show");
        
            // Đặt vị trí tooltip theo offset của container (đã định vị)
            const tooltipTop = box.offsetTop;
            const tooltipLeft = showLeft
            ? box.offsetLeft - tooltip.offsetWidth - 8
            : box.offsetLeft + box.offsetWidth + 8;
        
            tooltip.style.top = `0px`;
            tooltip.style.left = `${tooltipLeft}px`;
        
            // Sau khi tooltip hiển thị, ta mới đo được chính xác rect của tooltip
            requestAnimationFrame(() => {
            const tooltipRect = tooltip.getBoundingClientRect();
            const arrowOffset = boxRect.top + boxRect.height / 2 - tooltipRect.top;
        
            // Cập nhật biến CSS cho mũi tên căn giữa box
            tooltip.style.setProperty('--arrow-top', `${arrowOffset}px`);

            jQuery('.daotao-s3-container').addClass('daotao-s3-active');
            jQuery('.h-cauchuyen-contain').addClass('h-cauchuyen-contain-active');
            jQuery('#header').addClass('header-hidden');
            jQuery('.s-sukien-container').addClass('s-sukien-container-active');

            });
        });

        // box.addEventListener("mouseleave", () => {
        //     tooltip.classList.remove("show");            
        // });
    });

    // Click nút Close thì ẩn tooltip
    closeButton.addEventListener("click", () => {
        tooltip.classList.remove("show");
        overlayTooltip.classList.remove('show');
        currentBox = null;

        jQuery('.daotao-s3-container').removeClass('daotao-s3-active');
        jQuery('.h-cauchuyen-contain').removeClass('h-cauchuyen-contain-active');
        jQuery('#header').removeClass('header-hidden');
        jQuery('.s-sukien-container').removeClass('s-sukien-container-active');
    });


    const scrollDiv = document.getElementById('scrollDiv');
    scrollDiv.addEventListener('mouseenter', () => {
      // Chặn scroll của body
      document.body.classList.add('no-scroll');
    });

    scrollDiv.addEventListener('mouseleave', () => {
      // Cho phép scroll lại
      document.body.classList.remove('no-scroll');
    });
}

initTooltips();