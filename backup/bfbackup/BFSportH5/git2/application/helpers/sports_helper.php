<?php

/**
 * 返回图片地址
 * @param string $imageCode
 * @return string
 */
function getImageUrl($imageCode) {
    if (preg_match("/http(s)?:\/\//i", $imageCode)) {
        return $imageCode;
    } else {
        return IMG_DOMAIN . $imageCode;
    }
}

/**
 * 获得资讯类型对应的显示文本
 * @param $type
 */
function getNewsTypeText($type) {
    $data = array(
            'video' => '视频',
            'news' => '新闻',
            'gallery' => '图集',
            'collection' => '视频集',
            'program' => '节目',
            'activity' => '活动',
            'special' => '专题',
            'thread' => '话题',
            'topic' => '话题',
            'column' => '专栏'
    );
    return $data[$type];
}

/**
 * 获得资讯类型对应的显示文本图片
 * @param $type
 */
function getNewsTypeImg($type) {
    $data = array(
            'video' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAAqckyNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3BpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0Y1RDJDQjlBMkIzMTFFNkJDRTZBMTgwNjA1RTAwMEQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0Y1RDJDQjhBMkIzMTFFNkJDRTZBMTgwNjA1RTAwMEQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFOTU2OTE1NjVFMjIxMUU2QTJEMEQ4NDhBOEU3M0REMyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFOTU2OTE1NzVFMjIxMUU2QTJEMEQ4NDhBOEU3M0REMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgOLN1oAAAL4SURBVHja3Fg7jNNAEPXlAoUTCQpHF4mPOyTXFnQ+ymsRLandIaWnhpa05/poLUrcIHHukFJHXIX5SCeRAopbobuGWfRWGka7dpxzCjPSxLE3u54382Z2snufDg48klukj0jvkN70+imXpN9JPz44P/+1R8Bu082THgOyAXw7pI+HAPWV9AOp6hMKis7f69l06tPlMek9jWkA+nl9BCUAKmDQcnfAKFgHSnsjJg06tickjToGp+XGsMW8GWlJmgvD4oZ572qcFkNfsGdHpKOGNVdQp2wKTAFUIoAFMGTtmKfHTwUwAyZzzInAEO48Pf8He3ZxXWDasENGRx+RU4iEkZeWqGjjU8eakbg/xnftvIX4/THeVbShZROwkaDaGvc+IiElhSfLFjYoxoIvFid4NYzYGlgl+G+MD/Cy0FIM2hqhhCMCMEC+k9v06rrAbPyvy49NquCErRUg+pyaJpcKgOCSiPzrDNgMVFtuCSyBapmz53MWjUxUvgQUreCA+10Dm8FbJw373UXNeC6q6kREybNE5CmKR9XGg4MWoBILbWzAVEM+cdVrPttFxzNoAcoYM2PR8yyebioeOq9eW7qYxFKotpYmKh6xTTmE8QWqlBL0sNHK1W3YHBA7ctffJqJNEVPIqUK0Mwvwfo0xBcPMNQLowmLUoQVACF1tyYLWEStr9jdzrYTHjeErUSgM3QLRtXjIswpzAgHY20XENhXT+buoZGSJcr5mVdJs9G8YiAKUTjBeYf2Nu5ougIVsf1s5ik/KjF4ywDM4JWeRNy3WBGOnrLCoTYENO4iUaYozR5QCSw8YozCFyOHS0UBXGFPI2xTvWe0KWABAEesWFMu7hP0bCJnhoeg1F8LICOsa2vF1M8ydW+Z1GrGJw9s5jPFZVS1Fsckd+ajQuZw4DM9EK+YUfUqVskl9POv45/5sOk1N8bh09Gi9E5xUabka4JDRbJx+j0GN2L/9b//tgen+8/H4N335jGiNSfd7CugK28p7yruffwQYAGhJ25qiOZB9AAAAAElFTkSuQmCC',
            'news' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAAqckyNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3BpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTE2MzFFNTBBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTE2MzFFNEZBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3RjVEMkNCOEEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3RjVEMkNCOUEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PkNn1S8AAALMSURBVHja3FgxbNNAFL2mhsGJBIOjVgKSrWpnCzaHsR1R5szdkMLMXK/JnDlzxEgWJOoNqXOAiQCVIsUDDD2hduEfekZfp7N9dmwh86WXy519vv/u3v379t6ngwNB9oDwjPCIcF80024J14QPR5vNzz0i9pAqLxpMyETwjUM/T0HqG+E9QTaJBa3On/Lz4aFLxXPCE8WpBfmJJpLSCEpwUPa4xSSYR8ojnKIsYm7G805qIKfsnlOgn+o0IHQJ8xwifTjtE7aEqeG+M1y/IMRoU/16OX58JazznM0jpgYKWP0GznBTjl8RXmmrKdG+Snn2AuTPCSHafG0806QtqyAmDM6uDW3JfcrZjyArLRSgVn4MMhH6LzL6XNjKK4+YIjEjDAlvmWSUjdiKeAVkMoLcQvQNbVagqDmWe+sE5YKtTgBiu5hvkLbJogxJG61lGdUuQcQFBpjldUbUsw1IgpGTDB6bUFn1ip1CholNtOsTyDNpH6c8Z5USGVdw2sd+k9rYg5wIXJrYFfaN0KLW1DDrAhFry8J5EjFjC1+GWr1X5x6LNad6bKZNkXPFrgXoG/2LTMSx2F9nBmJDTU7bCnxZpEixNmLHrN5Geawd0Kb/RW2kEatdiiEIShZMQsgvZrITlnspK0JKrd6u8xxzceLPDW1TSLG7I6k0KXbZeLJqYgHbS4GWBwYs8ygjQ48FHh8E2mjro5xgrHnVxAZ4sDRkA2N2kJaJfucgkBwPN1h5ySZqVmbS8oj5cDxKOVwjOJRkJ0VtlpFZJFJcW7zbFU6pfJY69VHnTszZapXZY7Hl3gkQNV3bnNGxmFFPS5ciQ3ZyKaq3JRsreQFdVkWMh/DXKbOb9v40TWkvEgRkiT5/pXhrqd+iGXaprHwXw5cqZXeK2DWLfq5oqBGpNkvBvv+3H0z3X3Y6v+jPF6xWh7DfUEJ3eMV6d7TZ/PgtwAAJB8cTd0Zz+wAAAABJRU5ErkJggg==',
            'gallery' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAFddXwbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTZEQTQzODE1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTZEQTQzODA1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpjYjgyNzFkYy01ZmM4LTRiYjUtOTEwMC01MGJiNTRiM2E3YTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Zjk0ZDQzNGEtZGIyNy00YzAzLTgzMzUtMDA2MmQzMmVjNTcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+aXTMGQAABCJJREFUeNpi/P//P8N9HuY0BiyABSoxC4gLoGLrgfghiMGEpHAClP4KxCJgnUD8C4sCMAAIIMZ73ExY7QOClYxQB4E47kC8E0kyDdnOw1BaE9lOGPgGpa/DBAACCGyn4pe/s6BGYwMgk14D8RtcVoIAFzQs5JHCxBaIPYA4BmoIA3r4wMAsqFu5oHxjIN4BxEuAWA6mCCCAQE51BdKKDMSDX8jBRjRAdmIB1O3uSGL9ULoKlyZYEAch8VuhASEKpRnQ4wYGzqLxd0BT1nVsNnFB8RKk0NOERjQovqqRQ48LKQUQBQACCBbkLMAUsp3UUCQBgBwWAwoLRVBSRJPshyY/bAAUmIVI/JlIOUMDiG8gya1DzgNMOAx8CDWAG5qTJkDZbbDsDU3WmtBUexaKuZHYOzDKCzxeD4JqEIW6bBY0qbxGys4iULFvaHkNBgqJtawampDdoYYugYoVYFHngeRjY6haBmJ9xgUtCW5Agw4WVwxIyRQG3kANF4GqmYAvNQoA6TAG2oNfAAForVYdhIEgiMJiWn26ut+AQILhD0D3I0DXth6DqEUg+YhKQjV6DYa7ZjbZbO7aa6CXXNL3pLMzs+vAVvZga/dyRqCn3XdH497RYOVPM/psY/e6l74D+tGwbIXQujkYn0AO3JkC6w0bcPx2AixVwUgxajyLdOAU6JCo3HANTF2KP2xim8TCE1mEJMmEjyQDDGqEXXgvpiRIgZcqpAV5zFyJWhlVt1csWIK4egCUcN56ns0FjbkaQJoYMCkCnikKpbpENjhk5GmoZkM0JqhLGso6RSnX8YL5blI2klKaUT2O8HHCX1dQ8Q59rYwFSyOuZ4HQbcT9dgysHqGsDkw3R8/YJJn69CNzaBL/87p+BajGen0ThoIwIW+ms5PLsKtu9jeA3PTQYMG2utPV6OKxWJBLdTVkjgo8Zq/Ld+Ry6XuvNHskveSZNte++/XdfUct5t0z6t9Tar6xUcyonIO/x27jUwJM0R+KIuXoaJGj6bikashdwqYYkLEXzzIDONqEbHhQLRUieENWMBGWrUV3gR8WDYxvgctHAsErQNPB8M1cMMWbQHjgAOglwPbISRQ6wtbSsUk3YBEyzTQjAxRWrgt2MSyGMQm8PkM0XsApEsePK0ZHU+jKMngF3u8b9I9tLqk6FCeNPim8XzAuM8ZzqqmdxbgnxoNkyk1Fo7pZVIfiLFi9jEAQYjxbW+qi6VsZnBWKdyfUXmjQPfmqMdp0lNhoZQbOZqqx9B+g3ZryXQ0rGRTTTBoy9MwN9UFpOBeQ/4n6XAGQUjjrIKbI0kcqhpgI28hUIGUhIpbAARTxL/Fepis5cIwVQeCK2HWrKVc7PZ08rvxtCPZeGzLRJxj0Vx4RzT9zaGdaD8Jv+jzX40hPDbvo86PPtz7nX+EfDAHADA1jAAAAAElFTkSuQmCC',
            'collection' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEkAAAAcCAYAAAFaN6IOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTdEQUI3ODA1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTdEQUI3N0Y1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpjYjgyNzFkYy01ZmM4LTRiYjUtOTEwMC01MGJiNTRiM2E3YTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Zjk0ZDQzNGEtZGIyNy00YzAzLTgzMzUtMDA2MmQzMmVjNTcy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',
            'program' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAFddXwbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTk1NjkxNTc1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTk1NjkxNTY1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpjYjgyNzFkYy01ZmM4LTRiYjUtOTEwMC01MGJiNTRiM2E3YTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Zjk0ZDQzNGEtZGIyNy00YzAzLTgzMzUtMDA2MmQzMmVjNTcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+8CQfegAAA0VJREFUeNpi/P//P8N9HuY0BiyABSqxE4i5oGLXYZJMUPohEGtCJWZCMQMLEP+CKlgHpdNhOgECiPEeNxNW+4BgJSPUQe5AThCSLnkgdofZuRNJYibctViMg9sJEEBgOxW//J0FNBrEFwHiN0BsDMRnGXCDNHRT7YBYA4hvQA14CHUbMniIzTnroB5ahyRWhcRugzEAAgjkVFcgrchAPPgFCzYGpOB6SEgXExIbFIXuUFoTPWiBoABbMGtCE4YmNFCuI9mOmpLQAgIW7K04AgPDecigGp+fQKEHctI3EkKPASCAYEHOAkwh25FCkdoA5LAYUFgogpIikgQo5EWhyVIUKSBtgfgwlL0ES6yggzakaP+GK1PsRIpiZHAWOfkigcNY1GpiLS9wuArmG2No0oBlAVAh8BotrpZg0V9AKPHiA/LQIDOGZliyAAuR6h4ilznkAlBqFADSYQy0B78AAghkGT+QEQjEbDS06D4Q7wYFYzgokoHJ/xsN85knELuCEwjIIiwKjNESiAgFlm0HWYMtgcRgycSa0CT/BkrvhDqgCo8F6cSkxrNIlp1FKm5AKfIRWh4D5ccJWMyYSWrSn4CjlEAuQUSRQoKsfCYHxNxI5eROtHITOXhe4yiubIm1DN3wdWh8bBmeqiUILqBJoOQny7KZaImH6gkEbzKmpc8Y8FRFRPuMiUwfwRLGBCL1gfLpb3CTGVdLnMpgFUCAwaqYABqX+vQEoP7GBhYkTy1BLvxpWNvQEnBBS89AFlhM4ajRGHDUcmnQEnEJlsoJVCrOItAB4kKrLUkBZ/G0w2HirMTk6AIcLU5bXEU71OP4cj6sXnqIo8pgwGGfPLRCfUiNomoClhiLgTpoHZYYM4bG5FkizP5GgsdIimFiy+BWLA0rdxz1KHqMTUAeaqCkwqCFx6rx5LWdWGIO5jljAv3S63jqDGKzBE1qTUIlFKzpha8PVkBCf41hoDzGhZTENKHN9G8EavlZRJqdNpAxlobWxt1BZAEyYHmMWDCBRPXyaKNOxKQGmnvsLAXDBqAkWEhGbBE9SMYE7b2DmlAeQMzFQD/wjcp6QP1ID1iLEDZmCmoImwKxDKg5MkQbv7+B+AkQnwbiDwBMX8bI/pKnVQAAAABJRU5ErkJggg==',
            'activity' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAbCAYAAAFAcEyjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTdEQUI3ODQ1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTdEQUI3ODM1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpjYjgyNzFkYy01ZmM4LTRiYjUtOTEwMC01MGJiNTRiM2E3YTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Zjk0ZDQzNGEtZGIyNy00YzAzLTgzMzUtMDA2MmQzMmVjNTcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+ihr+sQAABG9JREFUeNpi/P//P8N9HuY0BmzgHjdTGkgBkEbGM0FiTEjqYoC4H8q+DtPpikUnA0gMIIAYQcYyYAcrGaEOEgFy7IB4HVSiCojfwOz0AOKvSLrkQQRMJ4p5il/+gr0HEEBgO4GcWSBBNEUgq94g8Y2B6s5C1aWxoDnQFohBkkFQt82EioMMEAEZDNScDhKAhw/UM4eBuABqWysQPwTiNqhh6cg2AAQQyKmuIKczEA9+gT2PxW8YABQgMMCERd4Wi1gV0NAqGAc5QFqhmAuKvyEFSDoumw5BEww22+SROciagqC0JhTDUlo6NGmghJ4H0OodxAYEUB03QABSylAHYRgIwwuZgqfgaXiJaTR+Gj0saDRIUo1Gj3eYr8Fwf/JdcimdWLjk0uzW3vr//Va3vLXiI5xb809E/fSTR50M3DqKARAZ2TPGyJD2RPohvOsh71xCZP2zBLSVjcnLCwtHatrZxnIS/1A5lk2Z50oGR32WJ1S+eLxbJss3+CcyB5wiFdmV1Oxdzdju/O0Yb6iVmn2gaGJOR33gf6zGj42c2amwZl1ZOwabEx+9inXrcSx6NPEiW3oJLI2PjuQrAK9Vy4MgFEWZmwYMGrT6ZtNM9gcYzXaokP0RkM1kOjOSyUQ3Mpmkm+9t5253z8tHEN/GcEzuefeej4eR/kr/uOhrMSHYU/P4oORPidRfeww1z/r2mgnqcXtqqQGsDTi3N2ACYy/5jIxasMCjVUIEPDFpBfAgCWZYjZDuCWAFs0IgFHeso/uIsRnQWneUDoFRYd8aKY+mRHgvZb4smR97wW4AjNizGKbmvMZCp1t4b3SCNHixhSAUE4fC85b9J2Ij3SHWXAlwKK5CFMhw963AztANjVhpnpouqUpgHnbnMGXlgsIMP7W+rhAG53cpdSZx5lt8hR0eNMUOUK+HEyBhp0AzBiy3dnW3vlBoRZb/TKcVQrjS48y+gvhPcWU+Z98GbI0gnk8dxB8BurFC3YZhIGqQkZVuOB9R3NLSKbhgqDi4xStt6YKLo8EVBxcHhxcbbGSO9E56suyLJWcDs1SQ1LHv3Z3fvbNs9vLLrP+X48v9PkZgrwB1iUmH3DDmjlAaROx8BEV/FxIpBVSJk9eDak1EnSzQhpTgmhL/3cG2U4qGyZJpo3I2tM6+a4IDLILwUCRMHtzkA4jwHTx2JpIUVn4CZ0l1jRHkM9UhE6lTNjfKReK8EuzeKsVzBWB3BZSBY5qJ/WpqoQyiVvEazuHHLGCQElukzxXgTko6bSIRkQanntjSktMqEdkOSCe9yiwRGy8G3II3pNo6UFg4YlsCHxsN6bAlQAwxBRo4d7OmonhySb3Tjp61iFnPEaKguJvcEcje0ybWObf3JIKdE9gBHrx5731jjKczTUTfGGLZBilaQ8sMdGXReQJqVmByo3RR0iZ1vFGKncjQHim3B5gV3rUBUZcPDGwkNN4pjZWWwn4UtTOzp3u9MZIbEJjUyXG9zym7c5RHiveSPRz4zuYoj3+rFX8AWY6Tvndj5J4AAAAASUVORK5CYII=',
            'special' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAFddXwbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTk1NjkxNTM1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTk1NjkxNTI1RTIyMTFFNkEyRDBEODQ4QThFNzNERDMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpjYjgyNzFkYy01ZmM4LTRiYjUtOTEwMC01MGJiNTRiM2E3YTIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Zjk0ZDQzNGEtZGIyNy00YzAzLTgzMzUtMDA2MmQzMmVjNTcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+2B2fYgAABElJREFUeNpi/P//P8N9HuY0BiyACSpxFogfAvEsIBYBYmMQmwmqCCT5Bsr+BsTXQQwWIP6FpAAEJsCMBQggxnvcTFjtA4KVjFAHBQE5X4HYA4iXQK1wh9n5ECrxGirxFWYnzL6z6OYCBBDYTsUvf2cBjWYgAaSxIHFA/k6DhgMMgMKDG+qEIKgvwUHAhKToLFQiDcl5QVADHwFxOtQvYAAQQCCnugJpRRKc+QsWbDABkOnrCOlC9ps7FH+F8ncCcRUQy8P8A4tBZE1voJHzDUnsBhSD/KiBzSaQaZpIoceA5FRjZMOQQ/Ab1E/uUKeBQD/UoOvIEQ4KPS40JxEEAAEEC3IWYArZTmIKIQWAHBYDCgtFUFJEkpCHSoJAAXJeQQozmJdfI+lhwKJuAlKQoQQ8DGggGQCKQTk0eTmkMJ6AZPE6aMzKQR37CF96YkBKRyAwE5pUGNCSz3WkoOnHoj8GV0JmwhPOIIsOQ7EHWiKGgTZoEIFSjig0sadDUxkXKZYdRmLvgKZh5AhvgxZYrVAaFk8FUNoWW2oUANJhDLQHvwACCGQZP5ARCMRsNLToPjDF74aV/EtIzaAkAk8g/gOOM6Ct33BkRFsqWbYdZA22pK+JVKhxIdWS6Jl6JjRhiEITSSuaunRi8hksE9tC85wcWuGJC1yHZo/XuNShJ313NP5XLEGLnMEnICV7TSh/AlLo4PXZGzRD3+DxyVloZn+E5BNjLBkfp2WwOiQNqokLR0YvQHO9BhJ9CJdlTHhcfRjq4m9IZR4DUgH8ECqXDi1N1kHjjJvUsvEsUpn3BmpROlpjRhQpFDSh5eFhqFwQMcGIngUK0Nt/UFAIbUnBykFQCqxGiuMJpFq2Doq5oIY+RArSh1BL0qGJwgOqjgtLfiTKMuS81YYmNgstyM8S0Sz4DW4y42qJUxmsAghQjdXCNAwF4Ylhhi1uoXqaIKeHBDS6uljQoLGtnt6Q+MkFPUlKkE9M1czwSr4jl+P9XJuWZJc80Sbte9+7n++7I4q5Hrjq/6c1/cbrmIFaUvEfUNENbRNk6c2YPOVhtFgG7nwZFbA8IrHuIt9/BpoQwnCiSbJMyCoJsAh8a1BWRkJu1+I9r+r8oqjreGY8pjINsFI8J6B/16HbhgzZmSDvW1ZjDS6XSuFLiC81wHwyngMslEDuhSTRiLYSkbIAmHMfa7UFVjuEGM0DKgcHaOwCHYPP0kju9BqKrvBJFd5yFRYjDpmB3tcMQML2W+BdxWcGfQOrEVIh4DlAbxzdKsmIQng8ZZWyhPp+Z3utWEqo8qstMB6KfApnmFpfRUJ0zsA8eTz7gGLyxtr6DfbOA5f2dxAHHvutfi0IOsWGc9EVLB03OxMHSxDasw65+hjoPrIuHnOFViUI0zfI2oliZDqQu9qaZuIDXrqya9LDP4ds/kN2Cun/A4dmpo0QvrRr2siRI9WJB7u+7NratNp/A3LNKrSByayVAAAAAElFTkSuQmCC',
            'thread' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAAqckyNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3BpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTE2MzFFNENBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTE2MzFFNEJBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3RjVEMkNCOEEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3RjVEMkNCOUEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PrMgaVQAAALTSURBVHja3Jg9bNNAFMfTKDBcBhhstRKUbBWZI9haxnZEmT0zN2s7hzWZO2eOOuIRvCFlDrCgGCpFsocy9Aa69B36W3p6unPs5EAyT3o99758v3vv3r147+v+fovkCelr0mekj1vNlN+kN6Sfj9brX3sE9pT+edtgIBvgdYf+vALUD9KPpLpJFGSdP+W3gwNFxRvSQ8PUhvu1mgglADUYjDxvMxfUTfdBwBl51KnQ35j4nHRBGlfo2yN9gbJPuiKdsj6mfuAYv0TZ3/CeD5sMUQVMA2pI2iWdoz4CQBdASoxZYQGpmC8gPcWcXAoYM+4lqw9RZqzukw+wFiyVwnJmYVfYXTP5HXvJGRZ2VWHOmVjcBOVCQI8BEtdxy06Nvku41MCxACPHNeYbbmhTzMJ9ZjmzcYlPsAJu6emsl7nSAJ6QCojCXXcGC3CGCjEvy1F/YukfOqyRWRYjA4C0dhGsBnjnCkfBiysORQRb4PyEDrdTaAtFvc19zi1jbWICzRfM4e2MzUUU5C45svS/wO6WBY8VAoeUxBJBt5ZNYLnnO7SwaF7SbrPcnW8w3xJWOCdTyxj9r8FORRAJsJCxCBIJc8ORiHLvRJ0W86ltPGdXsCLrmDvaI4trKYBfMghtiZgpAleRxfSwQfpvgimxqKQETErPAnOBMkVgSdDvDJF4gLlmlqTAG1gA15lsuSkBu7dSdo3wYHWMqyaDN2hsZuEBsW+wAC6U7fAzpw+AIbN0LM7tELA8n4zxHKEt9wUWAiwR95CqkSMWed8UzxFzzxUDcF3ICe7Q3LfFZpbzpDYktFxOsKglc72InbFc/FqQZ1GxBCH2BTZ2uJ/Z2feOMRMxJkPE4xnMJazXY7mp66LWVROHOmDakXKVycjiSq4Mx2uW08bnqrIktDGCL1VG7g3YDfN/1WCoLsuCfv63H0yNxW7NA+l3Y8IGA92D4fpovb59EGAA0cvJviUGb7cAAAAASUVORK5CYII=',
            'topic' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAcCAYAAAAqckyNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3BpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmOTRkNDM0YS1kYjI3LTRjMDMtODMzNS0wMDYyZDMyZWM1NzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MTE2MzFFNENBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTE2MzFFNEJBODdFMTFFNkE5MjBENUU4RjY0MjE4MUIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3RjVEMkNCOEEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3RjVEMkNCOUEyQjMxMUU2QkNFNkExODA2MDVFMDAwRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PrMgaVQAAALTSURBVHja3Jg9bNNAFMfTKDBcBhhstRKUbBWZI9haxnZEmT0zN2s7hzWZO2eOOuIRvCFlDrCgGCpFsocy9Aa69B36W3p6unPs5EAyT3o99758v3vv3r147+v+fovkCelr0mekj1vNlN+kN6Sfj9brX3sE9pT+edtgIBvgdYf+vALUD9KPpLpJFGSdP+W3gwNFxRvSQ8PUhvu1mgglADUYjDxvMxfUTfdBwBl51KnQ35j4nHRBGlfo2yN9gbJPuiKdsj6mfuAYv0TZ3/CeD5sMUQVMA2pI2iWdoz4CQBdASoxZYQGpmC8gPcWcXAoYM+4lqw9RZqzukw+wFiyVwnJmYVfYXTP5HXvJGRZ2VWHOmVjcBOVCQI8BEtdxy06Nvku41MCxACPHNeYbbmhTzMJ9ZjmzcYlPsAJu6emsl7nSAJ6QCojCXXcGC3CGCjEvy1F/YukfOqyRWRYjA4C0dhGsBnjnCkfBiysORQRb4PyEDrdTaAtFvc19zi1jbWICzRfM4e2MzUUU5C45svS/wO6WBY8VAoeUxBJBt5ZNYLnnO7SwaF7SbrPcnW8w3xJWOCdTyxj9r8FORRAJsJCxCBIJc8ORiHLvRJ0W86ltPGdXsCLrmDvaI4trKYBfMghtiZgpAleRxfSwQfpvgimxqKQETErPAnOBMkVgSdDvDJF4gLlmlqTAG1gA15lsuSkBu7dSdo3wYHWMqyaDN2hsZuEBsW+wAC6U7fAzpw+AIbN0LM7tELA8n4zxHKEt9wUWAiwR95CqkSMWed8UzxFzzxUDcF3ICe7Q3LfFZpbzpDYktFxOsKglc72InbFc/FqQZ1GxBCH2BTZ2uJ/Z2feOMRMxJkPE4xnMJazXY7mp66LWVROHOmDakXKVycjiSq4Mx2uW08bnqrIktDGCL1VG7g3YDfN/1WCoLsuCfv63H0yNxW7NA+l3Y8IGA92D4fpovb59EGAA0cvJviUGb7cAAAAASUVORK5CYII=',
            'column' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADYAAAAbCAYAAAFAcEyjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkMxNjVGRkEyOEVCQzExRTY4QjU2OEQwMDYwRUYwRDhFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkMxNjVGRkEzOEVCQzExRTY4QjU2OEQwMDYwRUYwRDhFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QzE2NUZGQTA4RUJDMTFFNjhCNTY4RDAwNjBFRjBEOEUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QzE2NUZGQTE4RUJDMTFFNjhCNTY4RDAwNjBFRjBEOEUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6QmGDTAAADp0lEQVR42mJkKP7PAARpDFgAC1RiFhBrAvE3IA4E4gn/exgYmJAUvgbih0D8CIhtYTrvQyXfQOl1MNUAAcQItBOrfUCwkgXJPhAQAeLDUHYaC5J9IFAANZ4LZieyfelIxhoDBBDMzllIgvJQVzMgWQXTzADyImMJQxoTmgIQ/orEBoFWXIHHgOakmWjO2gnE7mh8BoAAAjnVFUgrMhAPfoFs202CBrD/kJ2oCY3b9VD+dVhUAoEH1K/p6P56jUaDQBAQFyJFGhgwoQXGLLSQK4TStki2otgEc2IMWgiKwGIfSn8DhR7IvTtICAtugACCBTnIxu0MNADQ1ARyYQwTNG7RLeKCYpA3jZH4XEheRA5kBqg6YyjbHSlFgiz8hi0MGZCyFiwHTYDmBWRwHYvaNCyOSMeV5NENcofG+jc0+YdIvn8NtRA9mzAQyl/oYCcaH93Ab0gOSScm/phIiOvrWIKTAVpqEAUYoWUyqYUAqeA3KLsDBBDIMn5o3mejoWX3gSlyN6wUXoIlIVAzn3kCmX+YkCIbG7AlYJYxDnERNAtB+VgRWwJBzrwxBDJ0DFpqZSC2aoEBUSRD3+DJ0LZQdbbQIroN6iNRaEFAlGXIJe5ZHCXMdagFMVDaFs2XsCbBTkKWvUaqI/rxxBcXWpF2mJwS5A1SJHNjycBvkOLlG9T1mkjBS1YJUg01HIZtkSyXR0oE15HqZVv02pLYslEUybVBSG03BrTWEAyQFYzIpXsBNIjWYSmYkYE7MYU5Psu+ISVhXNXINxw1BN444yKixE/HEa+ES/sScPvqPqhsFIAWxKy0LogBAnRjrTAIw0B0IQ1iHo1Go9GzgAU9LLNUbxbN9PSCxYLGY/HoChSMXJPLpetvdCQ0WbLs096733utXGweuOv3OZ7v68gQqGAsE3I0DIZSQ3LHYoAi5QoqhmJIOti1R6oTC8Ctz3pSnTapziwNWGsoe6kwjrahwgFsDcByUsQcit2qe9gCq+Ci3r4qnptGTsg5AacJeDdCO7wx6rsxPB8TmvMGdtC8m1mIr4ioc06y4A69Xmja84lESnwjYhuFGswt2C0yOEQaeENpVrVQsvNgHv+klrUlPV0r/hcAYoqiKO93LdskrMR4CGCmRpAA4LJF3D4UYCP0bfGriE2gawkkFZsTuRUUfmnY7dZ98JsPMKqvYpRGkiDPIFWFo8NSh++5bn4GEmQoT6I8gAoAcSFgbebKiMOyLlEC5fE5JfhbrfgCbMrmS0ow280AAAAASUVORK5CYII='
    );
    return $data[$type];
}

/**
 * 获得时间格式 2016年8月25日 周三 19:30
 * @param $date 2017-02-26 13:00:00
 */
function getDateFormat($date) {
    $strtotime = strtotime($date);
    $di = date('w', $strtotime);
    $weekarray = array('日', '一', '二', '三', '四', '五', '六');
    $data = date('Y年m月d日', $strtotime) . '  周' . $weekarray[$di] . '  ' . date('H:i', $strtotime);
    return $data;
}

/**
 * Share看 WEB接口-h5调起App文档
 * App看 WEB接口文档
 * @param $type news,video,collection,gallery,program,match，topic，special，activity
 * @param $data 这个是数组，所有文档中需要的数据都加到这个数组中
 * @param $share_app 'app' or 'share'
 * @return string
 */
function getShareOrAppJson($type, $data, $share_app = 'share') {

    //news,video,collection,gallery,program,match，topic，special，activity

    $result = array();

    $title = (isset($data['title']) && $data['title']) ? $data['title'] : '';
    $brief = (isset($data['brief']) && $data['brief']) ? $data['brief'] : '';
    $site = (isset($data['site']) && $data['site']) ? $data['site'] : '';
    $image = (isset($data['image']) && $data['image']) ? getImageUrl($data['image']) : '';
    $large_image = (isset($data['large_image']) && $data['large_image']) ? getImageUrl($data['large_image']) : '';
    $url = (isset($data['url']) && $data['url']) ? $data['url'] : '';

    if ($share_app == 'share') {
        $title = urlencode($title);
        $brief = urlencode($brief);
        $site = urlencode($site);
        $image = urlencode($image);
        $large_image = urlencode($large_image);
        $url = urlencode($url);
    }

    $publishTm = (isset($data['publish_tm']) && $data['publish_tm']) ? $data['publish_tm'] : '';
    if ($publishTm && !is_numeric($publishTm)) {
        $publishTm = strtotime($data['publish_tm']);
    }

    switch ($type) {
        case 'news':
            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'image' => $image
                    , 'large_image' => $large_image
                    , 'publishTm' => $publishTm
            );

            $column = array();
            if (isset($data['column']) && $data['column'] && isset($data['column']['id']) && $data['column']['id']) {
                $column = $data['column'];
            }

            $column_title = '';
            if ($column && isset($column['title']) && $column['title']) {
                $column_title = $column['title'] = urlencode($column['title']);
            }

            if ($share_app == 'share') {
                $column_id = '';
                if ($column) $column_id = $column['id'];

                $result = array_merge($result, array('column_id' => $column_id, 'column_title' => $column_title));

            } else {
                if ($column) {
                    $result = array_merge($result, array('column' => $column));
                } else {
                    $result = array_merge($result, array('column' => (object)$column));
                }
            }
            break;

        case 'video':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'site' => $site
                    , 'image' => $image
                    , 'play_url' => $data['play_url']
                    , 'play_code' => $data['play_code']
                    , 'play_code2' => $data['play_code2']
                    , 'isvr' => $data['isvr']
                    , 'publishTm' => $publishTm
                    , 'render_mode' => (isset($data['render_mode']) && $data['render_mode']) ? $data['render_mode'] : ''
                    , 'low_latency' => (isset($data['low_latency']) && $data['low_latency']) ? $data['low_latency'] : 0
            );

            break;

        case 'collection':

            $result = array('type' => $type, 'id' => $data['id']);

            break;

        case 'gallery':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'image' => $image
                    , 'brief' => $brief
                    , 'publishTm' => $publishTm
            );

            break;

        case 'program':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'publishTm' => $publishTm
            );

            break;

        case 'match':

            $result = array('type' => $type, 'id' => $data['id']);

            break;

        case 'topic':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'postId' => (isset($data['postId']) && $data['postId']) ? $data['postId'] : ''
            );

            break;

        case 'special':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'image' => $image
                    , 'large_image' => $large_image
                    , 'brief' => $brief

            );

            break;

        case 'activity':

            $result = array(
                    'type' => $type
                    , 'id' => $data['id']
                    , 'title' => $title
                    , 'url' => $url
            );

            break;
    }
    return json_encode($result);
}

/**
 * H5视频播放器要的json数据
 * @param $videos 主要来自video表的数据 如果video_extra表有数据，请加入这个数组extra键值下面
 * @return string
 */
function getVideoPlayJson($videos) {

    if (isset($videos['extra']) && $videos['extra'] && isset($videos['extra']['box_cid']) && $videos['extra']['box_cid'] && isset($videos['extra']['file_size']) && $videos['extra']['file_size']) {
        $result = json_encode(
                array(
                        'id' => $videos['id']
                        , 'play_code' => array('cid' => $videos['extra']['box_cid'], 'size' => $videos['extra']['file_size'])
                        , 'play_code2' => $videos['play_code2']
                )
        );
    } else {
        $result = json_encode(
                array(
                        'id' => $videos['id']
                        , 'play_code' => (object)array()
                        , 'play_code2' => $videos['play_code2']
                )
        );
    }

    return $result;
}

/**
 * @param $page_template 静态模板名，必须和模板HTML文件名一致
 * @param string $title
 * @param string $page_type
 * @return array
 */
function getHeaderFooterData($page_template, $title = '', $page_type = '') {

    if (empty($title)) {
        $title = '体育直播_足球比赛_足球赛事_世界杯视频_暴风体育';
    }

    if (empty($page_type)) {
        $page_type = 'app';
    }

    $CI =& get_instance();

    $data = array(
            'page_template' => $page_template,
            'title' => $title,
            'keywords' => '体育直播,足球比赛,足球赛事,世界杯视频,2016里约奥运会',
            'description' => '暴风体育是一个专业的足球直播平台,为您提供足球直播，中超直播，英超直播，欧洲杯直播等国内外重大体育赛事的现场直播；最全的、最及时的足球直播,尽在暴风体育。',
            'resource' => $CI->config->item('template_resource')[$page_template],
            'page_type' => $page_type

    );
    return $data;
}

?>
