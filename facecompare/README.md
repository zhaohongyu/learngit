##::Face Verification
###::FaceVerification compares the faces in multiple different images and analyze their similarity.
##Usage notes :
> You need to upload a photo with only 1 face in as your base image. The face will be used to compare with faces in other images.

> Face Verification GET Example :
https://rekognition.com/func/api/?api_key=1234&api_secret=1234&jobs=face_compare&urls=https://rekognition.com/static/img/sample/brad_pitt_01.jpg&urls_compare=https://rekognition.com/static/img/sample/brad_pitt_02.jpg

> Face Verification POST example: (post to http://rekognition.com/func/api/)
如果想使用本地图片，就把 urls 改成 base64, urls_compare 改成 base64_compare.对应的value改成图片的base64 data即可

```
<?php
  $ch = curl_init();
  $data = array('api_key' => '1234', 
                'api_secret' => '5678', 
                'jobs' => 'face_compare',
                'urls' => 'https://rekognition.com/static/img/sample/brad_pitt_01.jpg',
                'urls_compare' => 'https://rekognition.com/static/img/sample/brad_pitt_02.jpg'
                );
  curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_exec($ch);
  curl_close($ch);
?>
```
> Response sample (json):

```
{
    "face_detection": [
        {
            "boundingbox": {
                "size": {
                    "height": 96.92,
                    "width": 96.92
                },
                "tl": {
                    "x": 61.54,
                    "y": 55.38
                }
            },
            "confidence": 1,
            "pose": {
                "pitch": -1.99,
                "roll": -5.86,
                "yaw": -9.89
            },
            "quality": {
                "brn": 0.5,
                "shn": 0.3
            }
        },
        {
            "boundingbox": {
                "size": {
                    "height": 112.31,
                    "width": 112.31
                },
                "tl": {
                    "x": 40,
                    "y": 52.31
                }
            },
            "confidence": 1,
            "matches": [
                {
                    "index": "1",
                    "score": "0.87"
                }
            ],
            "name": "1:0.87,",
            "pose": {
                "pitch": 6.56,
                "roll": -7.85,
                "yaw": -8.34
            },
            "quality": {
                "brn": 0.59,
                "shn": 1.4
            }
        }
    ],
    "ori_img_size": {
        "height": 317,
        "width": 214
    },
    "url": "https://rekognition.com/static/img/sample/brad_pitt_01.jpg",
    "usage": {
        "api_id": "On7QxkJMCOiyTt7k",
        "quota": 19997,
        "status": "Succeed."
    }
}
ERROR:
{
  "url":base64,
  "usage" {
    "quota":19979,
    "status":"ERROR! Image is corrupted!",
    "api_id":"On7QxkJMCOiyTt7k"
  }
}
```