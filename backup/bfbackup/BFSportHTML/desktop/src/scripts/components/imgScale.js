define(function () {
        /**
         * @param containers 选择器字符串,img容器的元素名或者.类名或者#ID名,多个容器用逗号分隔。参数示例 "selector1, selectorN"
         * @constructor
         */
        function imgScale(containers) {
            $(containers).find("img").each(function () {
                var imgJQ = $(this);

                //防止图片尺寸修改后,因为父元素没有隐藏造成溢出。
                imgJQ.parent().css({
                    "overflow": "hidden",
                    "display": "block"
                });

                //动画处理
                var transitionDurationValue = imgJQ.css("transition-duration");
                imgJQ.css("transition-duration", "0s");
                setTimeout(function () {
                    imgJQ.css("transition-duration", transitionDurationValue);
                }, 10);

                //如果图片元素的naturalWidth属性有值,则用naturalWidth,否则通过其他方式获得原始宽高
                if (this.naturalWidth) {
                    resize(this.naturalWidth, this.naturalHeight, imgJQ.width(), imgJQ.height(), imgJQ);
                } else {

                    // var new_img = new Image();
                    // new_img.src = imgJQ.attr("src");
                    // if (new_img.complete) {
                    //     console.log(new_img.width);
                    //     resize(new_img.width, new_img.height, imgJQ);
                    //     new_img = null;
                    // } else {
                    //     new_img.onload = function () {
                    //         console.log(new_img.width);
                    //         resize(new_img.width, new_img.height, imgJQ);
                    //         new_img = null;
                    //     };
                    // }

                    var constraintWidth = imgJQ.width();
                    var constraintHeight = imgJQ.height();
                    imgJQ.css({
                        "width": "auto",
                        "height": "auto"
                    });
                    resize(imgJQ.width(), imgJQ.height(), constraintWidth, constraintHeight, imgJQ);
                }

            });


        }

        /**
         *
         * @param naturalWidth 图片原始宽度
         * @param naturalHeight 图片原始高度
         * @param constraintWidth 约束宽度
         * @param constraintHeight 约束高度
         * @param imgJQ 图片元素JQuery对象
         */
        function resize(naturalWidth, naturalHeight, constraintWidth, constraintHeight, imgJQ) {
            var newWidth;
            var newHeight;

            if ((naturalWidth / constraintWidth) < (naturalHeight / constraintHeight)) {
                newWidth = constraintWidth;
                newHeight = newWidth / naturalWidth * naturalHeight;
            } else {
                newHeight = constraintHeight;
                newWidth = newHeight / naturalHeight * naturalWidth;
            }

            imgJQ.css({
                "width": newWidth + "px",
                "height": newHeight + "px"
            });
        }

        return imgScale;

    }
);
