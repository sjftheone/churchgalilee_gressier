<?php


namespace App\Form;

use App\Entity\Post;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


/**
 * Defines the form used to create and manipulate blog posts.
 */
class PostType extends AbstractType
{
    private $slugger;


    // Form types are services, so you can inject other services in them if needed
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('title', TextType::class, [
                'attr' => ['autofocus' => true,'maxlength' => 4],

                'attr' => array('style' => 'width: 600px')

                
            ])
            ->add('summary', TextareaType::class, [
                'help' => 'Sommaire',
                'label' => 'Résumé',
                'attr' => ['rows' => 3,'cols'=>100],
               
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20,'cols'=>100],
                'help' => 'help.post_content',
                'label' => 'Contenu',
                
            ])
            ->add('imageFile',FileType::class,['label'=>'Photo'],['required'=>false])
            ->add('publishedAt', DateTimeType::class, [

            //->add('publishedAt', DateTimePickerType::class, [
                'label' => 'publié le',
                'help' => 'date publication',
              //  'view_timezone' => $options['']->getTimezone()
            ])

            /*
            ->add('tags', TagsInputType::class, [
                'label' => 'label.tags',
                'required' => false,
            ])
            */
            // form events let you modify information or fields at different steps
            // of the form handling process.
            // See https://symfony.com/doc/current/form/events.html
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Post */
                $post = $event->getData();
                if (null !== $postTitle = $post->getTitle()) {
                    $post->setSlug($this->slugger->slug($postTitle)->lower());
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
