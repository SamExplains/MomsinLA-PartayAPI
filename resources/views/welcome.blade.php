@extends('layouts.master')

@section('title', 'MomsinLA')

@section('content')
    {{-- About image --}}
    <div class="col-12 text-center mt-4">
        <img src="{{ asset('img/about_us/about-banner.jpg') }}" class="img-fluid" alt="">
    </div>
    <div class="col-md-8 col-12 mt-5">
        <h4 class="font-weight-bold mb-4">About MomsinLA</h4>
        <section>
            <p>
                Established in 2017, MomsinLA have been serving the San Gabriel Valley’s local immigrant families with
                information on where to play, where to learn, and where to spend quality times together. MomsinLA combines
                all of the relevant content information in a translated format (in Chinese) for all of our community
                members.
            </p>
            <p>
                MomsinLA continues to be a leading non-profit organization (501(C)(3)) for enriching family values also
                through organizing both online and offline events that are tailored to the local immigrant families’ needs.
                Most of our events have been well recognized by local business leaders and partners, and we are able to
                combine forces to make a positive impact to our local immigrant communities.
            </p>
            <p>
                洛杉矶妈妈群成立于 2017 年，一直为圣盖博谷的当地移民家庭提供去哪玩和去哪学的信息平台。同时会结合当地社区领袖和商家举办各类线上和线下等活动来给华人家庭添加生活的乐趣。
            </p>
            <ul class="About_list">
                <li style="color:rgb(255, 107, 0);"><span style="color:black">2016年12月创立，501c(3)非营利机构</span></li>
                <li><span>平台包括官网，微信公众号，微信社群，及手机app</span></li>
                <li><span>公众号平台每日提供亲子活动咨询，目前已有19000会员（截止11/1/2021）</span></li>
                <li><span>微信社群共计46个, 包括教育群, 租凭群， 吃货群， 二手交易群， 团购群， 英语学习群等。</span></li>
                <li>
                    <span>线下活动包括六一儿童节，魔术秀，姜饼屋制作，知识论谈等。线上课程包括儿童牙科，幼童学琴，音乐启蒙，保险理财 考驾照，儿童心理健康，中文学习等。</span>
                </li>
            </ul>
        </section>
        <h4 class="font-weight-bold mt-4">Charitable Donation</h4>
        <section>
            <p>
                MomsinLA accepts all kinds of donations that will benefit our local community members. MomsinLA will pick up
                your donations and distribute them to our communities through organized Charity run.
            </p>
            <p>
                All donations will receive Tax-Deductible Donation Receipts at the items’ value. <br><br> Please contact <a
                    href="mailto:zhen@momsinla.com">zhen@momsinla.com</a> if you have any items you would like to donate.
            </p>
            <p>
                妈妈群接受各类以个人或企业名义的实物或现金捐赠。妈妈群可以协助来领取捐赠的物质并发放给当地的家庭。所有的捐赠都会收到妈妈群的非盈利机构抵税单。若有任何捐赠物资，请联系 <a
                    href="mailto:zhen@momsinla.com">zhen@momsinla.com</a>
            </p>
        </section>
    </div>
    <div class="col-md-4 col-12 mt-5 mb-5">
        <h4 class="font-weight-bold mt-4">妈妈群公众号</h4>
        <h4 class="font-weight-bold mt-4">Momsinla Wechat Public Platform</h4>
        <img src="{{ asset('img/about_qr.png') }}" class="img-fluid" alt="">

        <div class="contact p-5" style="border: 1px solid rgba(0, 0, 0, 0.2)">
            <h4 class="font-weight-bold">Contact</h4>
            <h6 class="mt-3 mb-3">We want to hear from you</h6>

            {{-- Form --}}
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control mb-2" name="" id="" aria-describedby="helpId"
                    placeholder="">
                <label for="">Company</label>
                <input type="text" class="form-control mb-2" name="" id="" aria-describedby="helpId"
                    placeholder="">
                <label for="">Message</label>
                <textarea type="text" class="form-control mb-2" name="" id="" aria-describedby="helpId"
                    placeholder=""></textarea>
            </div>
            <button type="button" class="btn" style="background: #F2711C; color:white"><span
                    class="pl-2 pr-2">Send</span></button>
            <div class="mt-3"><span class="font-weight-bold">Email:</span> ice@momsinla.com</div>
            <div class="mt-3"><span class="font-weight-bold">Phone:</span> 626-233-0171</div>
            <div class="mt-3"><span class="font-weight-bold">WeChat:</span> 13774200977</div>
        </div>
    </div>
@stop
