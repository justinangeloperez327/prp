<x-mail::message>
    <p>Order No: {{ $order->order_no }}</p>
    <p>Order Date: {{ $order->order_date->format('d/m/Y') }} (like it by: {{ $order->would_like_it_by }})</p>
    <p>
        OmiDesign<br>
        omi omi<br>
        10 Jomary Court<br>
        Berwick VIC 3806<br>
        Australia
    </p>
    <p>Email:<a href="mailto:omi@design.com">omi@design.com</a></p>
    <p>Order Details:</p>

    <x-mail::table>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Instructions</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{ $item['product'] }}</td>
                    <td>{{ $item['instructions'] ?? '' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ $item['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-mail::table>

    <p>Purchase Order No: {{ $order->purchase_order_no }}</p>
    <p>Additional Order Notes: {{ $order->additional_instructions }}</p>
</x-mail::message>
