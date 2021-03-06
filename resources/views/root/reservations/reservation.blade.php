<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $reservation->source }}</td>
    <td>{{ $reservation->name }}</td>
    <td data-image="{{ URL::to("{$reservation->user->file_directory}/thumbnails/{$reservation->user->file_name}") }}">
        {{ $reservation->user->full_name }}
    </td>
    <td>{{ $reservation->checkin_date }}</td>
    <td>{{ $reservation->checkout_date }}</td>
    <td>{{ Helper::moneyString($reservation->price_payable) }}</td>
    <td>{{ Helper::moneyString($reservation->price_paid) }}</td>
    <td>{{ $reservation->status_code }}</td>
    <td>
        <span class="d-flex" style="overflow: visible;">
            <div class="dropdown">
                <a href="javascript:void(0);" 
                    class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                    data-toggle="dropdown" 
                    title="Update status">
                    <i class="la la-ellipsis-h"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    @if(in_array(strtolower($reservation->status), ['reserved', 'pending']))
                        <a href="javascript:void(0);" class="dropdown-item update-reservation-to-paid"
                            data-form="#updateReservationToPaid" 
                            data-action="{{ route('root.reservations.update', $reservation) }}"
                            data-toggle="modal" 
                            data-target="#updateReservationToPaidConfirmation" 
                            data-status="paid"
                            data-reservation-user="{{ $reservation->user->titled_full_name }}" 
                            title="Set to paid">
                            Set to <span class="m--font-success">paid</span>
                        </a>
                    @endif

                    @if(in_array(strtolower($reservation->status), ['pending']))
                        <a href="javascript:void(0);" class="dropdown-item update-reservation-to-reserved"
                            data-form="#updateReservationToReserved" 
                            data-action="{{ route('root.reservations.update', $reservation) }}"
                            data-toggle="modal" 
                            data-target="#updateReservationToReservedConfirmation" 
                            data-status="reserved"
                            data-reservation-user="{{ $reservation->user->titled_full_name }}" 
                            title="Set to reserved">
                            Set to <span class="m--font-info">reserved</span>
                        </a>
                    @endif

                    @if(! in_array(strtolower($reservation->status), ['cancelled', 'void']))
                        <a href="javascript:void(0);" class="dropdown-item update-reservation-to-cancelled"
                            data-form="#updateReservationToCancelled" 
                            data-action="{{ route('root.reservations.update', $reservation) }}"
                            data-toggle="modal" 
                            data-target="#updateReservationToCancelledConfirmation" 
                            data-status="cancelled"
                            data-reservation-user="{{ $reservation->user->titled_full_name }}" 
                            title="Set to cancelled">Set to <span class="m--font-danger">cancelled</span>
                        </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('root.reservations.show', $reservation) }}" 
                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill show-reservation" 
                title="Show reservation">
                <i class="la la-eye"></i>
            </a>
        </span>
    </td>
</tr>