<?php

namespace Database\Seeders;

class DestinationData
{
    public static function getDestinations()
    {
        return [
            'vinh-ha-long' => [
                'name' => 'Vịnh Hạ Long',
                'slug' => 'vinh-ha-long',
                'description' => 'Một kỳ quan thiên nhiên thế giới với hàng ngàn hòn đảo đá vôi lớn nhỏ nổi bật trên mặt biển xanh ngắt.',
                'image' => 'https://nangluongsachvietnam.vn/userfile/User/dohuong/images/2023/2/11/ha-long-1-20230211162614120.jpg',
                'is_active' => true,
                'images' => [
                    'https://minio.halongbay.com.vn/halongbay/images/17593669111730084792tructhangvinhhalong.jpg',
                    'https://media-cdn-v2.laodong.vn/storage/newsportal/2023/10/12/1253579/Vinh-Ha-Long-1.jpg',
                    'https://nhandan.vn/special/30-nam-mot-chang-duong-di-san-Vinh-Ha-Long/assets/HLCklusX0n/things-to-do-in-ha-long-bay-banner-1-1920x1080.jpg',
                    'https://bcp.cdnchinhphu.vn/Uploaded/duongphuonglien/2020_07_06/ha%20long.jpg',
                    'https://bizweb.dktcdn.net/100/349/716/files/vinh-ha-long-1.png?v=1718616206397'
                ]
            ],
            'da-lat' => [
                'name' => 'Đà Lạt',
                'slug'=> 'da-lat',
                'description' => 'Một thành phố núi nổi tiếng với các biệt thự phong cách Pháp, vườn hoa và khí hậu mát mẻ.',
                'image' => 'https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2024/01/dia-diem-du-lich-da-lat-thumbnail.jpg',
                'is_active' => true,
                'images' => [
                    'https://www.dalattrip.com/media/2012/10/Dalat-Vietnam-Dalat-central-lake.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3n9eLyWrvwb6k1vwxdKRDGfX1CGM2YstMmvBvVvGeUsa31yntcm5Lu7MIkI6F2wc2p2Y&usqp=CAU',
                    'https://www.anywhere.com/img-a/destination/dalat-vietnam/Dalat-City-WebRes1-jpg',
                    'https://image.vietnam.travel/sites/default/files/styles/article_photo_large/public/2019-04/Dalat%20Vietnam%20Travel%20Guide-10.jpg?itok=sBAQzmxs'
                ]
            ],
            'hoi-an' => [
                'name' => 'Hội An',
                'slug'=> 'hoi-an',
                'description' => 'Một thị trấn cổ với kiến trúc được bảo tồn tốt, lễ hội đèn lồng và các nghề thủ công truyền thống.',
                'image' => 'https://images.vietnamtourism.gov.vn/vn//images/2023/thang_6/11125-quang_nam-huybank%40gmail.com-hoi_an_ve_dem_-1.jpg',
                'is_active' => true,
                'images' => [
                    'https://res.klook.com/klook-brand/image/upload/c_fill,w_420,h_260/v1695285940/1-%20IMAGES/Countries/Vietnam/Hoi%20An/_Vertical%20Generic/_Experiences:%20Local%20Leisure/Hoi%20An%20Ancient%20Town_Hoi%20An_Vietnam_AdobeStock_311987885.jpg',
                    'https://lh4.googleusercontent.com/-IYhWly2DZyk/VGwet6hdLWI/AAAAAAAABkw/WaTJ2lU_egM/s1600/Hoi%2BAn%2Btown.jpg',
                    'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/1a/5c/2c/streets-by-riverfront.jpg?w=900&h=500&s=1',
                    'https://upload.wikimedia.org/wikipedia/commons/f/f3/PhoCoHoiAn.jpg',
                    'https://hoiancreativecity.com/uploads/images/IMG_2157.jpeg'
                ]
            ],
            'nha-trang' => [
                'name' => 'Nha Trang',
                'slug'=> 'nha-trang',
                'description' => 'Một thành phố ven biển nổi tiếng với các bãi biển, cơ hội lặn biển và Tháp Chàm Po Nagar nổi tiếng.',
                'image' => 'https://hontamnhatrang.com/wp-content/uploads/2023/04/city-tour-nha-trang-01-1024x683.jpeg',
                'is_active' => true,
                'images' => [
                    'https://media.vneconomy.vn/w800/images/upload/2025/05/16/1-1.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTAIMOS3WBtB2IjprKu12dbKZjICmjfWNfk_kKEpsVSIR9HZt_3bVwgiS_kknZIgmmMpIs&usqp=CAU',
                    'https://sungetawaystravel.com/wp-content/uploads/2024/07/nha-trang-beaches-sun-getaways-travel-9.jpg',
                    'https://localvietnam.de/wp-content/uploads/2023/09/insel-diep-son-2-1024x683.jpg'
                ]
            ],
            'sapa' => [
                'name' => 'Sapa',
                'slug'=> 'sapa',
                'description' => 'Một thị trấn núi ở dãy Hoàng Liên Sơn, nổi tiếng với các ruộng bậc thang và các làng dân tộc thiểu số.',
                'image' => 'https://www.vietnamairlines.com/~/media/SEO-images/2025%20SEO/Traffic%20TA/MB/sapa-vietnam/sapa-vietnam-thumb_1.jpg',
                'is_active' => true,
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Th%E1%BB%8B_tr%E1%BA%A5n_Sa_Pa.jpg/960px-Th%E1%BB%8B_tr%E1%BA%A5n_Sa_Pa.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKxoGxTI4F8p5jfGN2SvzRjIYA3JchOaBTtg&s',
                    'https://danatravel.vn/data/tour/900/tour-ha-noi-sapa-2-ngay-1-dem-gia-re-1-1702868706.webp',
                    'https://cdn3.ivivu.com/2023/02/T%E1%BB%95ng-quan-du-l%E1%BB%8Bch-Sapa-ivivu.jpg',
                    'https://cuongdulich.com/assets/posts/1711550335-du-lich-sapa7.jpg'
                ]
            ],
            'phu-quoc' => [
                'name'=> 'Phú Quốc',
                'slug'=> 'phu-quoc',
                'description' => 'Một hòn đảo nổi tiếng với các bãi biển đẹp, khu nghỉ dưỡng sang trọng và các hoạt động giải trí đa dạng.',
                'image' => 'https://www.luavietours.com/wp/wp-content/uploads/2024/11/bai-sao-phu-quoc-750x460.jpg',
                'is_active' => true,
                'images' => [
                    'https://vcdn1-dulich.vnecdn.net/2022/04/08/dulichPhuQuoc-1649392573-9234-1649405369.jpg?w=0&h=0&q=100&dpr=2&fit=crop&s=SU6n3IvJxW1Sla0xqg31Kg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ_StQFddejbsPe82x92KO-HuxwednAp7NiDnxij1OGLdeBzYhV9JmmVgC-erS07lZ3dzg&usqp=CAU',
                    'https://media.vietnamplus.vn/images/16366538b3d107beaffc9c2a2a9df58414d8303a16a3b9e313041b50252c803521f63682708f85897f62d5d59356df454f65365ed5a63dd9a972506784b46fcda121d7244660aa57c804957f22cd6ef0cdb57feb901aea868e56e8a402356a73/phu-quoc-thanh-pho-bien-dao-mang-tam-quoc-te-10022025.jpg',
                    'https://images.vietnamtourism.gov.vn/vn/images/2025/thang_2/hon-may-rut_-phu-quoc_521615665-rs-900.jpg',
                ]
            ],
            'long-xuyen' => [
                'name'=>'Long Xuyên',
                'slug'=> 'long-xuyen',
                'description' => 'Thành phố nổi tiếng với các chợ nổi sầm uất và nền ẩm thực đa dạng.',
                'image' => 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/phuong_long_xuyen_cua_an_giang_2_ec48358610.jpg',
                'is_active' => true,
                'images' => [
                    'https://dantocmiennui-media.baotintuc.vn/images/57c5aab70c5efc5a98d240302ffc6edb70a3f18e63422fac1fe95822e317bba7aaec2b4361bd921c2900d8e534226f4b/l1-1.jpg',
                    'https://upload.wikimedia.org/wikipedia/commons/6/64/Nh%C3%A0_th%E1%BB%9D_ch%C3%ADnh_t%C3%B2a_th%C3%A0nh_ph%E1%BB%91_Long_Xuy%C3%AAn.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQR2tU1gYomAzze25uZIW1RLTIb90ACmK015w&s',
                    'https://vnanet.vn/Data/Articles/2022/11/26/6459142/vna_potal_thanh_pho_long_xuyen_-_trung_tam_do_thi_lon_cua_vung_dong_bang_song_cuu_long_stand.jpg',
                    'https://a.tcnn.vn//Images/images/unnamed(32).jpg',
                    'https://lh3.googleusercontent.com/proxy/2rmjcZFpDh0unOvWWeICCU_EOD0otYdJHvdyR6d9bl9yTDlRgGid1ReHllkwHRNdySzsZAD7c09-nXMyNbg83gypfDI6iKW4AjzNOyNJq0gY3ALvs23iZhNHl-2BMyQjcCeun_uHkL31_dg'
                ]
            ]
        ];
    }

    public static function getDefaultImages()
    {
        return [
            'https://www.outlooktravelmag.com/media/vietnam-1-1611926800.profileImage.2x-jpg-webp.webp',
            'https://static.independent.co.uk/2022/10/27/16/iStock-180697190.jpg'
        ];
    }
}
