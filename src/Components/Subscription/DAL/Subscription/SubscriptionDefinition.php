<?php declare(strict_types=1);

namespace Kiener\MolliePayments\Components\Subscription\DAL\Subscription;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class SubscriptionDefinition extends EntityDefinition
{

    public const ENTITY_NAME = 'mollie_subscription';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return SubscriptionEntity::class;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return SubscriptionCollection::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),

            # --------------------------------------------------------------------------------------------------------------------------

            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new ApiAware()),

            (new StringField('mollie_id', 'mollieId')),
            (new StringField('mollie_customer_id', 'mollieCustomerId')),

            new StringField('description', 'description'),
            new FloatField('amount', 'amount'),
            new IntField('quantity', 'quantity'),
            new StringField('currency', 'currency'),
            (new JsonField('metadata', 'metadata')),

            (new DateTimeField('next_payment_at', 'nextPaymentAt'))->addFlags(new ApiAware()),
            (new DateTimeField('last_reminded_at', 'lastRemindedAt'))->addFlags(new ApiAware()),
            (new DateTimeField('canceled_at', 'canceledAt'))->addFlags(new ApiAware()),

            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new ApiAware()),
            (new FkField('order_id', 'orderId', OrderDefinition::class))->addFlags(new ApiAware()),
            (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(new ApiAware()),

            new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class, 'id', false),

            # --------------------------------------------------------------------------------------------------------------------------
            # RUNTIME LOADED

            (new StringField('mollieStatus', 'mollieStatus'))->addFlags(new Runtime(), new ApiAware()),

            # --------------------------------------------------------------------------------------------------------------------------

            new CreatedAtField(),
            new UpdatedAtField(),
        ]);
    }
}
