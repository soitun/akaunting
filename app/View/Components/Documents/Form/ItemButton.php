<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Component;
use App\Models\Common\Item;

class ItemButton extends Component
{
    /** @var string */
    public $type;

    /** @var bool */
    public $isSale;

    /** @var bool */
    public $isPurchase;

    /** @var string */
    public $searchUrl;

    /** @var int */
    public $searchCharLimit;

    /** @var string */
    public $searchListKey;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type = 'sale',
        bool $isSale = false,
        bool $isPurchase = false,
        string $searchUrl = '',
        int $searchCharLimit = 3,
        string $searchListKey = 'value'
    ) {
        $this->type = $type;
        $this->isSale = $isSale;
        $this->isPurchase = $isPurchase;
        $this->searchUrl = $this->getSearchUrl($searchUrl);
        $this->searchCharLimit = $searchCharLimit;
        $this->searchListKey = $searchListKey;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $price_type = $this->getPriceType($this->type, $this->isSale, $this->isPurchase);

        $items = Item::priceType($price_type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        foreach ($items as $item) {
            $price = $item->{$price_type . '_price'};

            $item->price = $price;
        }

        $price = $price_type . '_price';

        return view('components.documents.form.item-button', compact('items', 'price'));
    }

    protected function getSearchUrl($url)
    {
        if (empty($url)) {
            return route('items.index');
        }

        return $url;
    }

    protected function getPriceType($type, $is_sale, $is_purchase)
    {
        if (!empty($is_sale)) {
            return 'sale';
        }

        if (!empty($is_purchase)) {
            return 'purchase';
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $type = 'purchase';
                break;
            case 'sale':
            case 'income':
            case 'invoice':
            default:
                $type = 'sale';
        }

        return $type;
    }
}
