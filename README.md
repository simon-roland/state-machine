# State Machine

A Laravel trait for managing state transitions.

## Installation

Install the package via composer:

```bash
composer require roland/state-machine
```

## Usage

To use the HasStateMachine trait, include it in your Eloquent model and implement the required methods.

```php
use Illuminate\Database\Eloquent\Model;
use Roland\StateMachine\Traits\HasStateMachine;

class Order extends Model
{
    use HasStateMachine;

    protected $fillable = ['state'];

    /**
     * Define the attribute that holds the current state.
     *
     * @return string
     */
    protected function getStateAttributeName(): string
    {
        return 'state';
    }

    /**
     * Define the allowed state transitions.
     *
     * @return array
     */
    protected function getAllowedTransitions(): array
    {
        return [
            'pending' => ['approved', 'rejected'],
            'approved' => ['shipped'],
            'shipped' => ['delivered', 'returned'],
        ];
    }
}
```

## Example Workflow

Here’s how you can interact with the state machine in your code:

```php
use App\Models\Order;

// Create a new order
$order = Order::create(['state' => 'pending']);

// Check the current state
echo $order->state; // Outputs: "pending"

// Check if a state transition is possible
if ($order->canTransitionTo('approved')) {
    $order->transitionTo('approved');
    echo $order->state; // Outputs: "approved"
} else {
    echo "Cannot transition to 'approved'.";
}

// Attempting an invalid transition throws an exception
try {
    $order->transitionTo('delivered'); // Invalid transition
} catch (\SimonRoland\StateMachine\Exceptions\InvalidStateTransitionException $e) {
    echo $e->getMessage(); // Outputs: "Invalid transition from 'approved' to 'delivered'."
}

// Successful transition
$order->transitionTo('shipped');
echo $order->state; // Outputs: "shipped"
```

## Working with Enums

You can use enums to define the allowed states and transitions:

```php
namespace App\Enums;

enum OrderState: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
    case SHIPPED = 4;
    case DELIVERED = 5;
    case RETURNED = 6;
}
```

```php
use App\Enums\OrderState;
use Illuminate\Database\Eloquent\Model;
use Roland\StateMachine\Traits\HasStateMachine;

class Order extends Model
{
    use HasStateMachine;

    protected $fillable = ['state'];

    protected $casts = [
        'state' => OrderState::class,
    ];

    /**
     * Define the attribute that holds the current state.
     *
     * @return string
     */
    protected function getStateAttributeName(): string
    {
        return 'state';
    }

    /**
     * Define the allowed state transitions.
     *
     * @return array
     */
    protected function getAllowedTransitions(): array
    {
        return [
            OrderState::PENDING->value => [OrderState::APPROVED, OrderState::REJECTED],
            OrderState::APPROVED->value => [OrderState::SHIPPED],
            OrderState::SHIPPED->value => [OrderState::DELIVERED, OrderState::RETURNED],
        ];
    }
}
```

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.

