<?php

declare(strict_types=1);

namespace VDOLog\Web\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use VDOLog\Core\Domain\Protocol;
use VDOLog\Web\Form\Dto\AddProtocolDto;

final class ProtocolType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => AddProtocolDto::class,
        ]);
    }

    /** @inheritDoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('parent', HiddenType::class, ['required' => false]);
        $builder->get('parent')->addModelTransformer(new CallbackTransformer(
            static function (?Protocol $protocol = null): ?string {
                return $protocol !== null ? $protocol->getId() : null;
            },
            function (?string $protocol = null): ?Protocol {
                if ($protocol === null) {
                    return null;
                }

                return $this->em->getRepository(Protocol::class)->find($protocol);
            }
        ));

        $builder->add(
            'sender',
            TextType::class,
            [
                'empty_data' => '',
                'required' => false,
                'attr' => ['placeholder' => 'Sender'],
            ]
        );
        $builder->add(
            'recipent',
            TextType::class,
            [
                'empty_data' => '',
                'required' => false,
                'attr' => ['placeholder' => 'Empfänger'],
            ]
        );
        $builder->add(
            'content',
            TextareaType::class,
            [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(['message' => 'Ein Funkspruch ist niemals leer!']),
                ],
            ]
        );
    }
}
