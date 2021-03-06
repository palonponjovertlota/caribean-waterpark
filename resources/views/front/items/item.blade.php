<div class="hotel-item">
    <div class="item-media">
        <div class="image-cover">
            <img src="{{ Helper::fileUrl($available_item->item->images->first(), 'thumbnail') }}"
                alt="" style="height: 100%; width: auto;">
        </div>
    </div>
    <div class="item-body">
        <!-- Name -->
        <div class="item-title">
            <h2>
                <a href="{{ route('front.items.show', $available_item->item) }}">{{ $available_item->item->name }}</a>
            </h2>
        </div>

        <!-- Stars -->
        <div class="item-hotel-star">
            @for($i = 1; $i <= $available_item->item->rating_stars; $i++)
                <i class="fa fa-star"></i>
            @endfor
        </div>

        <!-- Description -->
        <div>
            <p>{!! Str::limit($available_item->item->description, 40) !!}</p>
        </div>

        <div class="item-footer">
            <!-- Rating -->
            <div class="item-rate">
                <span>{{ Helper::decimalFormat($available_item->item->average_rating, 1) }}</span>
            </div>
            <div class="item-icon">
                <span class="text-warning">
                    Only <strong>{{ $available_item->calendar_unoccupied }}</strong> Left!
                </span>
            </div>
        </div>
    </div>

    <!-- Price -->
    <div class="item-price-more">
        <div class="price">one day from
            <span class="amount">
                {{ Helper::moneyString($available_item->item->price) }}
            </span>
        </div>

        <form method="POST" action="{{ route('front.reservation.add-item', $index) }}" style="margin-top: 36px;">
            {{ csrf_field() }}

            <button type="submit" class="awe-btn">Add this</button>
        </form>
    </div>

</div>